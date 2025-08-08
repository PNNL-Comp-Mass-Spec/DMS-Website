<?php
namespace App\Libraries;

class Sproc_sqlsrv extends Sproc_base {

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
        if (!is_resource($conn_id)) {
            throw new \Exception('Invalid value for $conn_id passed to method!');
        }

        $input_params->retval = 0;
        //$sql = "{? = CALL ".$sprocName." ("; // for "call" syntax, which ignores parameter names (must supply empty items in the query for skipped parameters)
        $sql = "EXEC ? = " . $sprocName; // Syntax reference: https://docs.microsoft.com/en-us/sql/t-sql/language-elements/execute-transact-sql?view=sql-server-2017
        $params = array();
        $params[] = array(&$input_params->retval, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_INT);
        $firstParam = true;

        reset($args);
        foreach ($args as $arg) {
            $paramName = '@' . $arg['name'];  // sproc arg name needs prefix
            //$paramType = constant($this->tpconv[$arg['type']]); // convert type name to constant

            $direction = SQLSRV_PARAM_IN;
            if ($arg['dir'] == 'output') {
                $direction = SQLSRV_PARAM_INOUT; // Alternatively SQLSRV_PARAM_OUT;
            }

            $size = ($arg['size']) ? $arg['size'] : -1;

            if ($arg['field'] == '<local>') {
                $fieldName = $arg['name'];     // Field name is <local>; use the argument name as the field name
            } else {
                $fieldName = $arg['field'];    // name of field member in param object (or null)
            }

            $fieldValue = $input_params->$fieldName;
//            echo "arg:'{$paramName}', var:'{$fieldName}', type:'{$paramType}',  dir:'{$direction}',  size:'{$size}', (value)'{$fieldValue}' <br>";

            if ($firstParam) {
                $sql = $sql . " " . $paramName . " = ?";
                $firstParam = false;
            } else {
                $sql = $sql . ", " . $paramName . " = ?";
            }

            // Format: array($value [, $direction [, $phpType [, $sqlType]]]) http://php.net/manual/en/function.sqlsrv-prepare.php
            // See https://github.com/Microsoft/msphpsql/blob/9eadf805adf83c3f7de53e06f2a7f2630a9fdd8f/source/sqlsrv/stmt.cpp for types supported
            if ($direction == SQLSRV_PARAM_IN) {
                $params[] = array($fieldValue, $direction);
            } else {
                $params[] = array(&$input_params->$fieldName, $direction);
            }
        }

        // If the stored procedure uses a Print statement before returning query results, sqlsrv reports an error
        // Disable this behavior
        // https://msdn.microsoft.com/en-us/library/cc626306(v=SQL.90).aspx
        sqlsrv_configure("WarningsReturnAsErrors", 0);

        //$sql = $sql.")}"; // for "call" syntax, which ignores parameter names (must supply empty items in the query for skipped parameters)
        //$stmt = sqlsrv_prepare($conn_id, $sql, $params);
        //$input_params->exec_result = sqlsrv_execute($stmt);
        //
        // Or to just use query
        $result = sqlsrv_query($conn_id, $sql, $params);

        $input_params->exec_result = $result;
        if (!$input_params->exec_result) {
            $ra_msg = sqlsrv_errors();
            $msg = "";
            foreach ($ra_msg as $error) {
                $msg = $msg . "\n" . $error["message"];
            }

            // Change this to true to see additional debug messages
            // @phpstan-ignore if.alwaysFalse
            if (false) {
                $msg = $msg . "\n" . $sql;
                //$msg = $msg."\nretval (SQLSRV_PARAM_OUT)";
                foreach ($params as $param) {
                    //$msg = $msg.", ".$param[0]." (".$param[1].")"; // Just output the enum integer value
                    $msg = $msg . ", " . $param[0] . " (";
                    if ($param[1] == SQLSRV_PARAM_IN) {
                        $msg = $msg . "SQLSRV_PARAM_IN";
                    }
                    if ($param[1] == SQLSRV_PARAM_OUT) {
                        $msg = $msg . "SQLSRV_PARAM_OUT";
                    }
                    if ($param[1] == SQLSRV_PARAM_INOUT) {
                        $msg = $msg . "SQLSRV_PARAM_INOUT";
                    }
                    $msg = $msg . ")";
                }
            }
            throw new \Exception($msg);
        }

        // Process the results here, before we call sqlsrv_free_stmt()
        $input_params->exec_result = new \stdClass();
        $input_params->exec_result->hasRows = false;
        if (sqlsrv_has_rows($result)) {
            $input_params->exec_result->hasRows = true;
            $metadata = $this->extract_field_metadata($result);
            $rows = $this->get_rows($result);
            $input_params->exec_result->metadata = $metadata;
            $input_params->exec_result->rows = $rows;
        }

        while ($res = sqlsrv_next_result($result)) {
            // Make sure all result sets are stepped through, since the output params may not be set until this happens
        }
        sqlsrv_free_stmt($result);
    }

    /**
     * Package results into array of arrays
     * @param resource $result
     * @return array
     */
    private function get_rows($result) {
        $result_array = array();
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $result_array[] = $row;
        }
        return $result_array;
    }

    /**
     * This builds up column metadata definitions https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-field-metadata?view=sql-server-2017
     * @param resource $result
     * @return array
     */
    private function extract_field_metadata($result) {
        // https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-field-metadata?view=sql-server-2017
        $tpconvSql = array(
            -5 => 'bigint',
            -2 => 'binary',
            -7 => 'bit',
            1 => 'char',
            91 => 'date',
            93 => 'datetime',
            //93 => 'datetime2',
            -155 => 'datetimeoffset',
            3 => 'decimal',
            6 => 'float',
            -4 => 'image',
            4 => 'int',
            //3 => 'money',
            -8 => 'nchar',
            -10 => 'ntext',
            2 => 'numeric',
            -9 => 'nvarchar',
            7 => 'real',
            //93 => 'smalldatetime',
            5 => 'smallint',
            //3 => 'Smallmoney',
            -1 => 'text',
            -154 => 'time',
            //-2 => 'timestamp',
            -6 => 'tinyint',
            -151 => 'udt',
            -11 => 'uniqueidentifier',
            -3 => 'varbinary',
            12 => 'varchar',
            -152 => 'xml'
        );
        $tpconv = array(
            'varchar' => 'char',
            'int' => 'int',
            'float' => 'real',
            'decimal' => 'real',
            'char' => 'char'
        );
        $metadata = array();
        foreach (sqlsrv_field_metadata($result) as $field) {
            $sqlType = $tpconvSql[$field['Type']];
            $F = new \stdClass();
            $F->name = $field['Name'];
            if (array_key_exists($sqlType, $tpconv)) {
                $F->type = $tpconv[$sqlType];
            } else {
                $F->type = $field['Type'];
            }
            //$F->type        = $field['Type'];
            //$F->type        = $tpconv[$sqlType];
            $F->max_length = $field['Size'];
            //$F->precision    = $field['Precision'];
            //$F->scale        = $field['Scale'];
            //$F->nullable    = $field['Nullable'];
            $F->sqlType = $sqlType;
            $metadata[] = $F;
        }
        return $metadata;
    }
}
?>
