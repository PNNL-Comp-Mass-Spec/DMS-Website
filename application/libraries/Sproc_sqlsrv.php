<?php
    if (!defined('BASEPATH')) {
        exit('No direct script access allowed');
    }

class Sproc_sqlsrv {

    // --------------------------------------------------------------------
    function __construct()
    {
    }

    /**
     * Call stored procedure given by $sprocName on database connection $conn_id
     * binding arguments to fields in $par as defined by specifications in $args.
     * Return results as fields in $par
     * @param string $sprocName
     * @param resource $conn_id
     * @param array $args
     * @param object $par
     * @throws Exception
     */
    function execute($sprocName, $conn_id, $args, $par)
    {
        $par->retval = 0;
        //$sql = "{? = CALL ".$sprocName." ("; // for "call" syntax, which ignores parameter names (must supply empty items in the query for skipped parameters)
        $sql = "EXEC ? = ".$sprocName; // Syntax reference: https://docs.microsoft.com/en-us/sql/t-sql/language-elements/execute-transact-sql?view=sql-server-2017
        $params = array();
        $params[] = array(&$par->retval, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_INT);
        $firstParam = true;
        reset($args);
        foreach($args as $arg) {
            $nm = '@'.$arg['name'];  // sproc arg name needs prefix
            //$tp = constant($this->tpconv[$arg['type']]); // convert type name to constant
            $dr = SQLSRV_PARAM_IN;
            if($arg['dir']=='output') { // convert direction to boolean
                $dr = SQLSRV_PARAM_INOUT; //SQLSRV_PARAM_OUT;
            }
            $sz = ($arg['size'])?$arg['size']:-1;
            if ($arg['field'] == '<local>') {
                $fn = $arg['name'];
            } else {
                $fn = $arg['field'];    // name of field member in param object (or null)
            }
//          echo "arg:'{$nm}', var:'{$fn}', type:'{$tp}',  dir:'{$dr}',  size:'{$sz}', (value)'{$par->$fn}' <br>";
            if($firstParam) {
                $sql = $sql." ".$nm." = ?";
                $firstParam = false;
            } else {
                $sql = $sql.", ".$nm." = ?";
            }
            // format: array($value [, $direction [, $phpType [, $sqlType]]]) http://php.net/manual/en/function.sqlsrv-prepare.php
            // see https://github.com/Microsoft/msphpsql/blob/9eadf805adf83c3f7de53e06f2a7f2630a9fdd8f/source/sqlsrv/stmt.cpp for types supported
            if($dr==SQLSRV_PARAM_IN) {
                $params[] = array($par->$fn, $dr);
            }
            else {
                $params[] = array(&$par->$fn, $dr);
            }
        }
               
        // If the stored procedure uses a Print statement before returning query results, sqlsrv reports an error
        // Disable this behavior
        // https://msdn.microsoft.com/en-us/library/cc626306(v=SQL.90).aspx
        sqlsrv_configure("WarningsReturnAsErrors", 0);

        //$sql = $sql.")}"; // for "call" syntax, which ignores parameter names (must supply empty items in the query for skipped parameters)
        
        //$stmt = sqlsrv_prepare($conn_id, $sql, $params);
        //$par->exec_result = sqlsrv_execute($stmt);
        // Or to just use query
        $stmt = sqlsrv_query($conn_id, $sql, $params);
        $par->exec_result = $stmt;
        if(!$par->exec_result) {
            $ra_msg = sqlsrv_errors();
            $msg = "";
            foreach ($ra_msg as $error) {
                $msg = $msg."\n".$error["message"];
            }
            if(false) { // set to false to disable these debug messages
                $msg = $msg."\n".$sql;
                //$msg = $msg."\nretval (SQLSRV_PARAM_OUT)";
                foreach ($params as $param) {
                    //$msg = $msg.", ".$param[0]." (".$param[1].")"; // Just output the enum integer value
                    $msg = $msg.", ".$param[0]." (";
                    if ($param[1] == SQLSRV_PARAM_IN) {
                        $msg = $msg."SQLSRV_PARAM_IN";
                    }
                    if ($param[1] == SQLSRV_PARAM_OUT) {
                        $msg = $msg."SQLSRV_PARAM_OUT";
                    }
                    if ($param[1] == SQLSRV_PARAM_INOUT) {
                        $msg = $msg."SQLSRV_PARAM_INOUT";
                    }
                    $msg = $msg.")";
                }
            }
            throw new Exception($msg);
        }
        //////
        // MUST PROCESS THE RESULTS HERE!!!! ( because they will be freed by sqlsrv_free_stmt()!)
        ///// $par->exec_result = resource (aka a table)
        $par->exec_result = new stdclass();
        $par->exec_result->hasRows = false;
        if(sqlsrv_has_rows($stmt)){
            $par->exec_result->hasRows = true;
            $metadata = $this->extract_field_metadata($stmt);
            $rows = $this->get_rows($stmt);
            $par->exec_result->metadata = $metadata;
            $par->exec_result->rows = $rows;
        }

        while($res = sqlsrv_next_result($stmt))
        {
            // make sure all result sets are stepped through, since the output params may not be set until this happens
        }
        sqlsrv_free_stmt($stmt);
    }

    /**
     * Package results into array of arrays
     * @param type $result
     * @return type
     */
    private function get_rows($result)
    {
        $result_array = array();
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $result_array[] = $row;
        }
        return $result_array;
    }

    /**
     * This builds up column metadata definitions https://docs.microsoft.com/en-us/sql/connect/php/sqlsrv-field-metadata?view=sql-server-2017
     * @param type $result
     * @return \stdClass
     */
    private function extract_field_metadata($stmt)
    {
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
            -2 => 'timestamp',
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
        foreach (sqlsrv_field_metadata($stmt) as $field ) {
            $sqlType = $tpconvSql[$field['Type']];
            $F              = new stdClass();
            $F->name        = $field['Name'];
            if (array_key_exists($sqlType, $tpconv)) {
                $F->type    = $tpconv[$sqlType];
            }
            else {
                $F->type    = $field['Type'];
            }
            //$F->type      = $field['Type'];
            //$F->type      = $tpconv[$sqlType];
            $F->max_length  = $field['Size'];
            //$F->precision = $field['Precision'];
            //$F->scale     = $field['Scale'];
            //$F->nullable  = $field['Nullable'];
            $F->sqlType     = $sqlType;
            $metadata[] = $F;
        }
        return $metadata;
    }

    // --------------------------------------------------------------------
    // (someday) 'varchar' => constant('SQLVARCHAR'), ??
    // conversion of sproc arg data type
    // from config definition to SQL Server binding value
    // (this list is partial)
    //private $tpconv = array(
    //  'varchar' => 'SQLVARCHAR',
    //  'int' => 'SQLINT4',
    //  'tinyint' => 'SQLINT1',
    //  'real' => 'SQLFLT4',
    //  'text' => 'SQLTEXT',
    //  'smallint' => 'SQLINT2',
    //  'char' => 'SQLCHAR'
    //);

}
