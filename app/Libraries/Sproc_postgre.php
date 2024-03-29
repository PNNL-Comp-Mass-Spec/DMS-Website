<?php
namespace App\Libraries;

class Sproc_postgre extends Sproc_base {

    /**
     * Call stored procedure given by $sprocName on database connection $conn_id
     * binding arguments to fields in $input_params as defined by specifications in $args.
     * Returns results as fields in $input_params
     * @param string $sprocName Stored procedure name
     * @param resource $conn_id Database connection ID, from  $this->db->connID
     * @param array $args Stored procedure arguments; see AddLocalArgument in Sproc_base or get_sproc_arg_defs in S_model
     * @param object $input_params
     * @throws Exception
     */
    function execute($sprocName, $conn_id, $args, $input_params) {
        $input_params->retval = 0;
        $sql = "CALL ".$sprocName." (";
        $params = array();
        $outParams = array();
        $firstParam = true;
        $paramCounter = 1;
        reset($args); // Reset the iterator...
        foreach ($args as $arg) {
            $paramName = '_' . $arg['name'];  // sproc arg name needs prefix

            if ($arg['field'] == '<local>') {
                $fieldName = $arg['name'];     // Field name is <local>; use the argument name as the field name
            } else {
                $fieldName = $arg['field'];    // name of field member in param object (or null)
            }

            $arg['fieldName'] = $fieldName;

            if ($arg['dir']=='output') { // convert direction to boolean
                $outParams[] = $arg;
                continue;
            }

            if ($firstParam) {
                $sql = $sql . " " . $paramName . " => $" . $paramCounter;
                $firstParam = false;
            } else {
                $sql = $sql . ", " . $paramName . " => $" . $paramCounter;
            }

            $paramCounter++;
            $params[] = $input_params->$fieldName;
        }

        $sql = $sql.")";

        // This might have been needed initially, but now using it causes 'invalid transaction termination' errors due to transaction nesting.
        //pg_query($conn_id, "BEGIN");
        //$result = pg_query_params($conn_id, $sql, $params); // Use pg_send_query_params() and pg_get_results() to be able to use pg_result_error()
        $result = pg_send_query_params($conn_id, $sql, $params);

        // Process the results here, before we call pg_free_result()
        $input_params->exec_result = new \stdClass();
        $input_params->exec_result->hasRows = false;

        if (!$result) {
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
            $fieldcode = array(
            "PGSQL_DIAG_SEVERITY",        "PGSQL_DIAG_SQLSTATE",
            "PGSQL_DIAG_MESSAGE_PRIMARY", "PGSQL_DIAG_MESSAGE_DETAIL",
            "PGSQL_DIAG_MESSAGE_HINT",    "PGSQL_DIAG_STATEMENT_POSITION",
            "PGSQL_DIAG_CONTEXT",         "PGSQL_DIAG_SOURCE_FILE",
            "PGSQL_DIAG_SOURCE_LINE",     "PGSQL_DIAG_SOURCE_FUNCTION");

            $msg = "";
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

        $retMetadata = $this->get_metadata_assoc($this->extract_field_metadata($result));
        $retRows = $this->get_rows($result);

        $matched = false;
        // PostgreSQL: only a single row is ever returned from a single stored procedure.
        //   table data is returned via the refcursors specified in the returned row.
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

                $cursorResult = pg_query($conn_id, "FETCH ALL IN " . $row[$colName]);
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
        pg_query($conn_id, "COMMIT");
    }

    /**
     * Package results into array of arrays
     * @param type $result
     * @return type
     */
    private function get_rows($result) {
        $result_array = array();
        while ($row = pg_fetch_assoc($result)) {
            $result_array[] = $row;
        }
        return $result_array;
    }

    /**
     * Convert metadata definitions to an associative array
     * @param type $metadata
     * @return \stdClass
     */
    private function get_metadata_assoc($metadata)
    {
        $metadataAssoc = array();
        foreach ($metadata as $column) {
            $metadataAssoc[$column->name] = $column;
        }

        return $metadataAssoc;
    }

    /**
     * This builds up column metadata definitions https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-field-metadata?view=sql-server-2017
     * @param type $result
     * @return \stdClass
     */
    private function extract_field_metadata($result) {
        // from comment at https://www.php.net/manual/en/function.pg-field-type.php
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
            'smallint' => 'int2',
            'smallserial' => 'int2',
            'int4' => 'int4',
            'integer' => 'int4',
            'serial' => 'int4',
            'int4range' => 'int4range',
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
            'xml' => 'xml',
            'refcursor' => 'cursor'
        );

        $tpconv = array(
            'varchar' => 'char',
            'int' => 'int',
            'int4' => 'int',
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
            $fieldData->type = $pg_to_php[$fieldData->sqlType];

            if (array_key_exists($fieldData->sqlType, $tpconv)) {
                $fieldData->type = $tpconv[$fieldData->sqlType];
            }

            $metadata[] = $fieldData;
        }

        return $metadata;
    }
}
?>
