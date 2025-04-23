<?php
namespace App\Libraries;

class Sproc_postgre extends Sproc_base {

    /**
     * Call stored procedure given by $sprocName on database connection $conn_id
     * binding arguments to fields in $input_params as defined by specifications in $args.
     * Returns results as fields in $input_params
     * @param string $sprocName Stored procedure name
     * @param resource|object|string $conn_id Database connection ID, from  $this->db->connID
     * @param array $args Stored procedure arguments; see AddLocalArgument in Sproc_base or get_sproc_arg_defs in S_model
     * @param object $input_params
     * @param array $formFields Form fields
     * @throws \Exception
     */
    function execute($sprocName, $conn_id, $args, $input_params, $formFields) {
        if (is_resource($conn_id) || (!is_string($conn_id) && get_class($conn_id) !== 'PgSql\Connection')) {
            throw new \Exception('Invalid value for $conn_id passed to method!');
        }

        try {
            $this->executeInternal($sprocName, $conn_id, $args, $input_params, $formFields);

            // Ensure the transaction is committed, and cursors closed - this may trigger a warning if no transaction or already committed
            // If it throws an error, it should only be due to a broken pipe
            pg_query($conn_id, "COMMIT");
        } catch (\Exception $ex) {
            // Rollback any changes in the transaction, and also close any cursors - this may trigger a warning if no transaction or already rolled back
            // If it throws an error, it should only be due to a broken pipe
            pg_query($conn_id, "ROLLBACK");

            throw $ex;
        }

        // Commit any changes in the transaction.
        // Commented out 2024-12-11
        // pg_query($conn_id, "COMMIT");
    }

    /**
     * Call stored procedure given by $sprocName on database connection $conn_id
     * binding arguments to fields in $input_params as defined by specifications in $args.
     * Returns results as fields in $input_params
     * @param string $sprocName Stored procedure or function name
     * @param \PgSql\Connection|string $conn_id Database connection ID, from  $this->db->connID
     * @param array $args Stored procedure arguments; see AddLocalArgument in Sproc_base or get_sproc_arg_defs in S_model
     * @param object $input_params
     * @param array $formFields Form fields
     * @throws \Exception
     */
    private function executeInternal($sprocName, $conn_id, $args, $input_params, $formFields) {
        $input_params->retval = 0;
        $sql = "CALL ".$sprocName." (";
        $params = array();
        $outParams = array();
        $firstParam = true;
        $paramCounter = 1;
        reset($args); // Reset the iterator...

        $procType = $this->isSProcFunction($sprocName, $conn_id);
        $isFunction = $procType === 'f';

        if ($isFunction) {
            // Can use 'SELECT $sprocName', but that just gives back a single-column table of stringified rows.
            // Use 'SELECT * FROM $sprocName' to get them as a proper table.
            $sql = "SELECT * FROM ".$sprocName." (";
        }

        foreach ($args as $arg) {
            $paramName = '_' . $arg['name'];  // sproc arg name needs prefix

            if ($arg['field'] == '<local>') {
                $fieldName = $arg['name'];     // Field name is <local>; use the argument name as the field name
            } else {
                $fieldName = $arg['field'];    // name of field member in param object (or null)
            }

            $arg['fieldName'] = $fieldName;

            if ($arg['dir'] == 'output') {
                // Store the argument in the output params array
                $outParams[] = $arg;
            }

            $dataType = trim(strtolower($arg['type']));

            $isTimestampOrDate = $dataType == 'datetime' ||
                                 $dataType == 'date' ||
                                 $dataType == 'timestamp' ||
                                 $dataType == 'timestamptz';

            $outputArgument = $arg['dir'] == 'output';

            if ($isFunction && $outputArgument && $paramName == '_message') {
                // In DMS, by convention, each procedure has output arguments _message and _returnCode
                // However, functions do not have those output arguments

                // Although we could auto-skip this argument, it is better to update the sproc_args table
                // in the model Config DB to remove the message field
            }

            $appendParameter = true;
            $valueToUse = '';

            if ($outputArgument) {
                if (empty($input_params->$fieldName)) {
                    // The current value for the input/output field is undefined
                    // We need to pass the default value to the field to assure that PostgreSQL can resolve the procedure
                    // based on the data types of the parameter values

                    // First see if the form_fields table in the config DB has a default value defined,
                    // either in the "default" column or in the "rules" column using "default_value[0]"
                    // If not defined there, use $this->getDefaultValue($dataType)

                    $valueDefined = false;
                    $formFieldDefaultValue = $this->getFormFieldDefaultValue($formFields, $arg['field'], $dataType, $valueDefined);

                    if ($valueDefined) {
                        $valueToUse = $formFieldDefaultValue;
                    } else {
                        $valueToUse = $this->getDefaultValue($dataType);
                    }

                    if ($valueToUse == '' && $isTimestampOrDate) {
                        // Use the current date and time, e.g. '2024-05-28 18:51:00'
                        $valueToUse = date('Y-m-d H:i:s');
                    }
                } else {
                    // Field is an input/output field with a defined value; pass the value to the procedure
                    // However, if the value is numeric, pass an actual number
                    $valueToUse = $this->getValueToUse($dataType, $input_params->$fieldName);

                    if ($valueToUse == '' && $isTimestampOrDate) {
                        // Use the current date and time, e.g. '2024-05-28 18:51:00'
                        $valueToUse = date('Y-m-d H:i:s');
                    }
                }
            } else {
                $valueToUse = $this->getValueToUse($dataType, $input_params->$fieldName);

                if ($valueToUse == '' && $isTimestampOrDate) {
                    // Do not append this parameter (since appending an empty string will result in a data conversion error)
                    // Instead, use the default value defined by the procedure or function
                    $appendParameter = false;
                }
            }

            if (!$appendParameter) {
                continue;
            }

            if ($firstParam) {
                $sql = $sql . " " . $paramName . " => $" . $paramCounter;
                $firstParam = false;
            } else {
                $sql = $sql . ", " . $paramName . " => $" . $paramCounter;
            }

            $paramCounter++;

            $params[] = $valueToUse;
        }

        $sql = $sql.")";

        // NOTE: if the function or stored procedure starts a transaction with 'BEGIN;', you will see a 'nested transaction' error.
        // Start a transaction before calling the procedure; cursors (for returning table data) only work inside transactions.
        pg_query($conn_id, "BEGIN");

        // $queryParamsResult = pg_query_params($conn_id, $sql, $params); // Use pg_send_query_params() and pg_get_results() to be able to use pg_result_error()
        $queryParamsResult = pg_send_query_params($conn_id, $sql, $params);

        // Process the results here, before we call pg_free_result()
        $input_params->exec_result = new \stdClass();
        $input_params->exec_result->hasRows = false;

        if (!$queryParamsResult) {
            // TODO: report some kind of error; if sending the query failed, our connection is probably bad.
            return;
        }

        $result = pg_get_result($conn_id);
        if (!$result) {
            // TODO: report some kind of error; if getting query results failed, our connection has probably died.
            return;
        }

        $errors = pg_result_error($result);
        if ($errors != "") {
            $msg = sprintf("<br />\n%s<br />\n", pg_result_error_field($result, constant("PGSQL_DIAG_MESSAGE_PRIMARY")));

            if (ENVIRONMENT === 'development') {
                // Also show the extended details of the error on development instances
                $msg = $msg."<br />\n";

                $fieldcode = array(
                "PGSQL_DIAG_SEVERITY",        "PGSQL_DIAG_SQLSTATE",
                "PGSQL_DIAG_MESSAGE_PRIMARY", "PGSQL_DIAG_MESSAGE_DETAIL",
                "PGSQL_DIAG_MESSAGE_HINT",    "PGSQL_DIAG_STATEMENT_POSITION",
                "PGSQL_DIAG_CONTEXT",         "PGSQL_DIAG_SOURCE_FILE",
                "PGSQL_DIAG_SOURCE_LINE",     "PGSQL_DIAG_SOURCE_FUNCTION");

                foreach ($fieldcode as $fcode)
                {
                    $msg = $msg."ERROR:".sprintf("%s: %s<br />\n", $fcode, pg_result_error_field($result, constant($fcode)));
                }

                // Change this to true to see additional debug messages
                if (false) {
                    $msg = $msg . "\n" . $sql;
                    foreach ($params as $param) {
                        $msg = $msg.", ".$param[0];
                    }
                }
            }

            pg_free_result($result);
            throw new \Exception($msg);
        }

        if (pg_num_rows($result) == 0) {
            // No results returned, nothing to process. Assume success.
            $input_params->retval = 0;

            // Commit any changes in the transaction.
            pg_query($conn_id, "COMMIT");

            return;
        }

        if ($isFunction) {
            // At this time, we do not have any PostgreSQL functions that have 'OUT'/'INOUT' parameters, so we will skip handling that case.
            $metadata = $this->extract_field_metadata($result);
            $rows = $this->get_rows($result);

            $input_params->exec_result->hasRows = true;
            $input_params->exec_result->metadata = $metadata;
            $input_params->exec_result->rows = $rows;

            pg_free_result($result);

            // Commit any changes in the transaction.
            pg_query($conn_id, "COMMIT");

            return;
        }

        $retMetadata = $this->get_metadata_assoc($this->extract_field_metadata($result));
        $retRows = $this->get_rows($result);

        // PostgreSQL: only a single row is ever returned from a single stored procedure
        // Table data is returned via the refcursors specified in the returned row

        $matchedCols = 0;
        $row = $retRows[0];
        reset($outParams); // Reset the iterator...

        foreach (array_keys($row) as $colName) {
            if (strcasecmp("_returnCode", $colName) == 0 || strcasecmp("_return", $colName) == 0) {
                $input_params->retval = $row[$colName];
                if ($input_params->retval == "") {
                    $input_params->retval = 0;
                }
                $matchedCols++;
                continue;
            }

            $colMatched = false;
            foreach ($outParams as $param) {
                if (strcasecmp("_".$param['name'], $colName) == 0) {
                    $fieldName = $param['fieldName'];
                    $input_params->$fieldName = $row[$colName];
                    $matchedCols++;
                    $colMatched = true;
                    break;
                }
            }

            if ($colMatched) {
                continue;
            }

            if (strcasecmp($retMetadata[$colName]->sqlType, 'refcursor') == 0) {
                if ($input_params->exec_result->hasRows) {
                    echo "Multiple refcursors not supported; ignoring additional refcursor\n";
                    continue;
                }

                $cursorResult = pg_query($conn_id, "FETCH ALL FROM " . $row[$colName]);

                if ($cursorResult && pg_num_rows($cursorResult) > 0) {
                    $metadata = $this->extract_field_metadata($cursorResult);
                    $rows = $this->get_rows($cursorResult);

                    $input_params->exec_result->hasRows = true;
                    $input_params->exec_result->metadata = $metadata;
                    $input_params->exec_result->rows = $rows;
                }
            }
        }

        pg_free_result($result);

        // Commit any changes in the transaction.
        // Commented out 2024-12-11
        // pg_query($conn_id, "COMMIT");
    }

    /**
     * Package results into array of arrays
     * @param \PgSql\Result $result
     * @return array
     */
    private function get_rows($result): array {
        $result_array = array();
        while ($row = pg_fetch_assoc($result)) {
            $result_array[] = $row;
        }
        return $result_array;
    }

    /**
     * Determines if the stored procedure given by $sprocName on database connection $conn_id is actually a function
     * Returns 'f' for function, or 'p' for procedure, or 'u' for unknown/not found.
     * @param string $sprocName Stored procedure name
     * @param \PgSql\Connection|string $conn_id Database connection ID, from  $this->db->connID
     * @throws \Exception
     */
    private function isSProcFunction($sprocName, $conn_id) {
        $schema = 'public';
        $spResult = pg_query($conn_id, "SHOW search_path");

        if ($spResult) {
            while ($row = pg_fetch_assoc($spResult)) {
                $sp = $row["search_path"];
                $sp2 = explode(",", $sp);
                $schema = trim($sp2[0]);
                break;
            }
        }

        pg_free_result($spResult);

        $result = pg_query($conn_id, 'SELECT prokind FROM pg_proc WHERE pronamespace = \''.$schema.'\'::regnamespace AND proname = \''.$sprocName.'\'');
        //$result = pg_query($conn_id, 'SELECT routine_type AS prokind FROM information_schema.routines WHERE specific_schema = \''.$schema.'\' AND specific_name LIKE \''.$sprocName.'_%\''); // 'FUNCTION' or 'PROCEDURE'

        $val = 'u';
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $val = $row["prokind"];
                break;
            }
        }

        pg_free_result($result);
        return $val;
    }

    /**
     * Convert metadata definitions to an associative array
     * @param array $metadata
     * @return array
     */
    private function get_metadata_assoc($metadata): array
    {
        $metadataAssoc = array();
        foreach ($metadata as $column) {
            $metadataAssoc[$column->name] = $column;
        }

        return $metadataAssoc;
    }

    /**
     * This builds up column metadata definitions https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-field-metadata?view=sql-server-2017
     * @param \PgSql\Result $result
     * @return array
     */
    private function extract_field_metadata($result): array {
        // From comment at https://www.php.net/manual/en/function.pg-field-type.php
        $pg_to_php = array(
            'bit' => 'bit',
            'boolean' => 'bool',
            'box' => 'box',
            'character' => 'bpchar',
            'char' => 'bpchar',
            'bytea' => 'bytea',
            'cidr' => 'cidr',
            'circle' => 'circle',
            'date' => 'date',
            'daterange' => 'daterange',
            'real' => 'float4',
            'double precision' => 'float8',
            'inet' => 'inet',
            'int2' => 'int2',
            'smallint' => 'int2',
            'smallserial' => 'int2',
            'int4' => 'int4',
            'integer' => 'int4',
            'serial' => 'int4',
            'int4range' => 'int4range',
            'int8' => 'int8',
            'bigint' => 'int8',
            'bigserial' => 'int8',
            'int8range' => 'int8range',
            'interval' => 'interval',
            'json' => 'json',
            'lseg' => 'lseg',
            'macaddr' => 'macaddr',
            'money' => 'money',
            'decimal' => 'numeric',
            'numeric' => 'numeric',
            'numrange' => 'numrange',
            'path' => 'path',
            'point' => 'point',
            'polygon' => 'polygon',
            'text' => 'text',
            'time' => 'time',
            'time without time zone' => 'time',
            'timestamp' => 'timestamp',
            'timestamp without time zone' => 'timestamp',
            'timestamptz' => 'timestamptz',
            'timestamp with time zone' => 'timestamptz',
            'time with time zone' => 'timetz',
            'tsquery' => 'tsquery',
            'tsrange' => 'tsrange',
            'tstzrange' => 'tstzrange',
            'tsvector' => 'tsvector',
            'uuid' => 'uuid',
            'bit varying' => 'varbit',
            'character varying' => 'varchar',
            'varchar' => 'varchar',
            'citext' => 'text',
            'xml' => 'xml',
            'refcursor' => 'cursor'
        );

        $tpconv = array(
            'varchar' => 'char',
            'int' => 'int',
            'int2' => 'int',
            'int4' => 'int',
            'int8' => 'int',
            'float' => 'real',
            'decimal' => 'real',
            'double precision' => 'real',
            'numeric' => 'real',
            'char' => 'char',
            'text' => 'char',
            'timestamp' => 'datetime',
            'timestamptz' => 'datetime'
        );

        $metadata = array();
        $fields = pg_num_fields($result);

        for ($i = 0; $i < $fields; $i++) {
            $fieldData = new \stdClass();
            $fieldData->name = pg_field_name($result, $i);
            $fieldData->sqlType = pg_field_type($result, $i);

            $fieldData->type = $fieldData->sqlType;
            if (array_key_exists($fieldData->sqlType, $pg_to_php)) {
                $fieldData->type = $pg_to_php[$fieldData->sqlType];
            }

            if (array_key_exists($fieldData->type, $tpconv)) {
                $fieldData->type = $tpconv[$fieldData->type];
            }

            $metadata[] = $fieldData;
        }

        return $metadata;
    }

    /**
     * Get the default value for a form field (if defined)
     * @param array $formFields
     * @param string $formFieldName
     * @param mixed $dataType
     * @param mixed $valueDefined
     * @return string|float|int
     */
    private function getFormFieldDefaultValue(array $formFields, string $formFieldName, $dataType, &$valueDefined) {
        $valueDefined = false;

        foreach ($formFields as $formField) {
            if ($formField['name'] != $formFieldName) {
                continue;
            }

            $formFieldDefault = $formField['default'];

            if (!empty($formFieldDefault)) {
                $valueDefined = true;
                return $this->getValueToUse($dataType, $formFieldDefault);
            }

            $rules = $formField['rules'];

            $rule_list = explode('|', $rules);

            foreach($rule_list as $ruleValue) {
                if (stripos(trim($ruleValue), 'default_value') === 0) {
                    // Find the opening square bracket
                    $bracketPos1 = strpos($ruleValue, '[');

                    // Find the last closing square bracket
                    $bracketPos2 = strrpos($ruleValue, ']', $bracketPos1);

                    if ($bracketPos1 > 0 && $bracketPos2 > $bracketPos1 + 1) {
                        $defaultValue = substr($ruleValue, $bracketPos1 + 1, $bracketPos2 - $bracketPos1 - 1);

                        $valueDefined = true;
                        return $this->getValueToUse($dataType, $defaultValue);
                    }
                }
            }
        }

        return '';
    }

    /**
     * Get default value for the given data type
     * @param string $dataType Data type
     * @return string|float|int|bool
     */
    private function getDefaultValue($dataType) {
        switch ($dataType) {
            case 'integer':
            case 'tinyint':
            case 'smallint':
            case 'int2':
            case 'int':
            case 'int4':
            case 'bigint':
            case 'int8':
                return 0;

            case 'char':
            case 'character':
            case 'citext':
            case 'text':
            case 'varchar':
                return '';

            case 'real':
            case 'float':
            case 'float4':
            case 'double':
            case 'double precision':
            case 'float8';
            case 'numeric':
                return 0.0;

            case 'boolean':
            case 'bool':
                return false;

            case 'datetime':
            case 'date':
            case 'timestamp':
            case 'timestamptz':
                // Return an empty string; the calling procedure should skip this parameter
                return '';
        }

        return '';
    }

    /**
     * If the value is numeric, format it by the given data type
     * @param string $dataType
     * @param string $value
     * @return mixed
     */
    private function getValueToUse(string $dataType, string $value) {

        if (!empty($value)) {
            // Cast the value to the appropriate data type
            switch ($dataType) {
                case 'integer':
                case 'tinyint':
                case 'smallint':
                case 'int2':
                case 'int':
                case 'int4':
                case 'bigint':
                case 'int8':
                    return (int)$value;

                case 'char':
                case 'character':
                case 'citext':
                case 'text':
                case 'varchar':
                    return $value;

                case 'real':
                case 'float':
                case 'float4':
                case 'numeric':
                    return (float)$value;

                case 'double':
                case 'double precision':
                case 'float8';
                    return (double)$value;

                case 'boolean':
                case 'bool':
                    return (boolean)$value;

                case 'datetime':
                case 'date':
                case 'timestamp':
                case 'timestamptz':
                    // Leave the value as a string; the PostgreSQL DB driver for PHP
                    // will auto-convert dates to a timestamp (for timestamp arguments on a procedure or function)
                    return $value;

                default:
                    return $value;
            }
        }

        $defaultValue = $this->getDefaultValue($dataType);
        return $defaultValue;
    }

}
?>
