<?php  
    if (!defined('BASEPATH')) {
        exit('No direct script access allowed');
    }

require("Sproc_base.php");
class Sproc_mssql extends Sproc_base {

    /**
     * Call stored procedure given by $sprocName on database connection $conn_id
     * binding arguments to fields in $input_params as defined by specifications in $args.
     * Returns results as fields in $input_params
     * @param string $sprocName
     * @param resource $conn_id
     * @param array $args
     * @param object $input_params
     * @throws Exception
     */
    function execute($sprocName, $conn_id, $args, $input_params)
    {
        $stmt = mssql_init($sprocName, $conn_id);
        if(!$stmt) {
            throw new Exception("Statement initialization failed for $sprocName");
        }
        
        reset($args);
        foreach($args as $arg) {
            $paramName = '@'.$arg['name'];  // sproc arg name needs prefix
            $paramType = constant($this->tpconv[$arg['type']]); // convert type name to constant

            $isOutput = $arg['dir'] == 'output'; // convert direction to boolean
            
            $size = ($arg['size']) ? $arg['size'] : -1;
            
            if ($arg['field'] == '<local>') {
                $fieldName = $arg['name'];     // Field name is <local>; use the argument name as the field name
            } else {
                $fieldName = $arg['field'];    // name of field member in param object (or null)
            }    

//            echo "arg:'{$paramName}', var:'{$fieldName}', type:'{$paramType}',  dir:'{$isOutput}',  size:'{$size}', (value)'{$input_params->$fieldName}' <br>";
            
            $ok = mssql_bind($stmt, $paramName, $input_params->$fieldName, $paramType, $isOutput, false, $size);
            if(!$ok) {
                throw new Exception("Error trying to bind field '$fieldName'");
            }
        }
        mssql_bind($stmt, "RETVAL", $input_params->retval, SQLINT2);  // always bind to return value from sproc
        
        $input_params->exec_result = mssql_execute($stmt);
        
        if(!$input_params->exec_result) {
            $ra_msg = mssql_get_last_message();
            throw new Exception($ra_msg);
        }

        // Process the results here, before we call mssql_free_statement()
        $result = $input_params->exec_result;
        
        $input_params->exec_result = new stdclass();
        $input_params->exec_result->hasRows = false;
        if(is_resource($result)){
            $input_params->exec_result->hasRows = true;
            $metadata = $this->extract_field_metadata($result);
            $rows = $this->get_rows($result);
            $input_params->exec_result->metadata = $metadata;
            $input_params->exec_result->rows = $rows;
        }
        mssql_free_statement($stmt);        
    }

    /**
     * Package results into array of arrays
     * @param type $result
     * @return type
     */
    private function get_rows($result)
    {
        $result_array = array();
        while ($row = mssql_fetch_assoc($result)) {
            $result_array[] = $row;
        }
        mssql_free_result($result);
        return $result_array;
    }
    
    /**
     * This builds up column metadata definitions
    // (it is copied from CI mssql_result driver)
     * @param type $result
     * @return \stdClass
     */
    private function extract_field_metadata($result)
    {
        $metadata = array();
        while ($field = mssql_fetch_field($result)) {    
            $F                 = new stdClass();
            $F->name         = $field->name;
            $F->type         = $field->type;
            $F->max_length    = $field->max_length;
            $metadata[] = $F;
        }
        return $metadata;
    }
    
    // --------------------------------------------------------------------
    // (someday) 'varchar' => constant('SQLVARCHAR'), ??
    // conversion of sproc arg data type
    // from config definition to SQL Server binding value
    // (this list is partial)
    private $tpconv = array(
        'varchar' => 'SQLVARCHAR', 
        'int' => 'SQLINT4',  
        'tinyint' => 'SQLINT1',
        'real' => 'SQLFLT4',
        'text' => 'SQLTEXT',  
        'smallint' => 'SQLINT2',  
        'char' => 'SQLCHAR'
    );
    
}
