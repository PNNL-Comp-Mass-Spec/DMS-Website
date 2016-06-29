<?php  
	if (!defined('BASEPATH')) {
		exit('No direct script access allowed');
	}

class Sproc_mssql {

	// --------------------------------------------------------------------
	function __construct()
	{
	}

	// --------------------------------------------------------------------
	// call stored procedure given by $sprocName on database connection $conn_id
	// binding arguments to fields in $par as defined by specifications in $args.
	// return results as fields in $par
	function execute($sprocName, $conn_id, $args, $par)
	{
		$stmt = mssql_init($sprocName, $conn_id);
		if(!$stmt) {
			throw new Exception('Statement initialization failed');
		}

		reset($args);
		foreach($args as $arg) {
			$nm = '@'.$arg['name'];  // sproc arg name needs prefix
			$tp = constant($this->tpconv[$arg['type']]); // convert type name to constant
			$dr = $arg['dir']=='output'; // convert direction to boolean
			$sz = ($arg['size'])?$arg['size']:-1;
			if ($arg['field'] == '<local>') {
				$fn = $arg['name'];
			} else {
				$fn = $arg['field'];	// name of field member in param object (or null)
			}			
//			echo "arg:'{$nm}', var:'{$fn}', type:'{$tp}',  dir:'{$dr}',  size:'{$sz}', (value)'{$par->$fn}' <br>";
			$ok = mssql_bind($stmt, $nm, $par->$fn, $tp, $dr, false, $sz);
			if(!$ok) {
				throw new Exception("Error trying to bind field '$fn'");
			}
		}
		mssql_bind($stmt, "RETVAL", $par->retval, SQLINT2);  // always bind to return value from sproc
		
		$par->exec_result = mssql_execute($stmt);
		if(!$par->exec_result) {
			$ra_msg = mssql_get_last_message();
			throw new Exception($ra_msg);
		}
		mssql_free_statement($stmt);		
	}

	// --------------------------------------------------------------------
	function get_rowset($result)
	{
		// package results into array of arrays
		$result_array = array();
		while ($row = mssql_fetch_assoc($result)) {
			$result_array[] = $row;
		}
		mssql_free_result($result);
//		mssql_next_result($result);
		return $result_array;
	}
	
	// --------------------------------------------------------------------
	// this builds up column metadata definitions
	// (it is copied from CI mssql_result driver)
	function extract_col_metadata($result)
	{
		$metadata = array();
		while ($field = mssql_fetch_field($result)) {	
			$F 				= new stdClass();
			$F->name 		= $field->name;
			$F->type 		= $field->type;
			$F->max_length	= $field->max_length;
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
