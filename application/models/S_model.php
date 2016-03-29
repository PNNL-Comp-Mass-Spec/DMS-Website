<?php
// The function of this clase is to execute a stored procedure
// against one of the databases defined in the application/config/database file.
// It gets the procedure name and arguments from a config db as defined by the
// config_name and config_source. If the stored procedure returns a rowset,
// it is automatically saved and made accessible to external code.

// helper class for stored procedure arguments:
//   basic definition of object that will contain 
//   bound arguments for calling stored procedure
//   only the baseline canonical arguments are 
//   statically defined by the class - sproc-specific
//   arguments are added dynamically
class Bound_arguments {
	var $retval = -1;
	var $mode = '';
	var $message = '';
	var $callingUser = '';
}

// main class
class S_model extends CI_Model {

	// some names used for caching
	const col_info_storage_name_root = "col_info_";
	private $col_info_storage_name = "";
	//
	const total_rows_storage_name_root = "total_rows_";
	private  $total_rows_storage_name = "";
	
	private $config_name = '';
	private $config_source = '';
	private	$configDBFolder = '';
	
	
	// object that contains database-specific code used to actually access the stored procedure
	private $sproc_handler = NULL;
	
	// actual name of stored procedure
	// may be different then config_name, which can reference aliases in general parameters table in config db
	private $sprocName = '';
	
	// definition of stored procedure arguments from config db
	private $sproc_args = array();

	// database connection group from config db (general parameters table)
	private $dbn = 'default';
	
	// object whose fields are bound to actual arguments used for calling sproc
	private $bound_calling_parameters = NULL; // of type Bound_arguments

	// if stored procedure returned a rowset, it gets stored here
	private $result_array = NULL;
	// and information about its columns gets stored here
	private $column_info = NULL;
	
	private $error_text = '';
	
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->configDBFolder = $this->config->item('model_config_path');
	}

	// (someday) see if we can figure out how to get bound values updated when rowset is returned (mssql_next_result is not working )
	
	// --------------------------------------------------------------------
	function init($config_name, $config_source = "ad_hoc_query")
	{
		$this->error_text = '';
		try {
			$this->config_name = $config_name;
			$this->config_source = $config_source;
			$this->col_info_storage_name = self::col_info_storage_name_root.$this->config_name.'_'.$this->config_source;
			$this->col_info_storage_name = self::col_info_storage_name_root.$this->config_name.'_'.$this->config_source;
			$this->total_rows_storage_name = self::total_rows_storage_name_root.$this->config_name.'_'.$this->config_source;
			
			$dbFileName = $config_source . '.db';
			
			$this->_clear();
	
			$this->set_my_sproc_handler('sproc_mssql'); // (someday) pass in via argument (or constructor?)

			$this->get_sproc_arg_defs($config_name, $dbFileName);
			return TRUE;
		} catch (Exception $e) {
			$this->error_text = $e->getMessage();
			return FALSE;
		}
	}
	
	// --------------------------------------------------------------------
	// initializes stored procedure, binds arguments to paramObj members and 
	// local variables, and calls the stored procedure, returning the result
	function execute_sproc($parmObj)
	{	
		$this->error_text = '';
		try {
			if(!isset($parmObj)) throw new Exception('Input parameter object was not supplied');

			$CI =& get_instance();
			$my_db = $CI->load->database($this->dbn, TRUE, TRUE);

			// bind arguments to object
			// - create fields in local param object and bind sproc args to them
			// - set values of local object fields from corresponding fields in input object, if present
			// 
			$this->bound_calling_parameters = new Bound_arguments();
			foreach($this->sproc_args as $arg) {
				$fn = ($arg['field'] == '<local>')?$arg['name']:$arg['field'];
		//		$b->fn = $fn; // ??
				if(isset($parmObj->$fn)) {
					$this->bound_calling_parameters->$fn = $parmObj->$fn;
				} else {
					$this->bound_calling_parameters->$fn = '';
				}
			}  // $this->bound_calling_parameters = $this->get_calling_args($parmObj); ??
			
			// execute sproc
			$this->sproc_handler->execute($this->sprocName, $my_db->conn_id, $this->sproc_args, $this->bound_calling_parameters);

			// what was the result?
			$result = $this->bound_calling_parameters->exec_result;

			// dissapointing...
			if(!$result) {
				throw new Exception('Execution failed');
			}

			// figure out what kind of result we got, and handle it
			if(is_resource($result)) { // rowset
 				// extract col metadata
				$this->column_info = $this->sproc_handler->extract_col_metadata($result);
				$this->cache_column_info();
	
				// package results into array of arrays
				$this->result_array = $this->sproc_handler->get_rowset($result);
				$this->cache_total_rows();
			} else {
				$sproc_return_value = $this->bound_calling_parameters->retval;
				if($sproc_return_value != 0) {
					throw new exception($this->bound_calling_parameters->message . ' (' . $sproc_return_value . ')');
				}
			}

			return true;
		} catch (Exception $e) {
			$this->error_text = $e->getMessage();
			return false;
		}
		
	}

	// --------------------------------------------------------------------
	private
	function cache_total_rows()
	{
		$CI =& get_instance();
		$CI->load->helper('cache');

		save_to_cache($this->total_rows_storage_name, count($this->result_array));
	}	

	// --------------------------------------------------------------------
	private
	function cache_column_info()
	{
		$CI =& get_instance();
		$CI->load->helper('cache');

		save_to_cache($this->col_info_storage_name, $this->column_info);
	}	

	// --------------------------------------------------------------------
	function get_rows()
	{
		return $this->result_array;
	}

	// --------------------------------------------------------------------
	function get_filtered_rows($sorting_filter, $paging_filter)
	{
		$rows = $this->result_array;

		$CI =& get_instance();
		$CI->load->library('table_sorter');
/*		
		foreach($sorting_filter as $sort) {
			$col = $sort['qf_sort_col'];
			$dir = $sort['qf_sort_dir'];
			if($col) {
				$rows = $CI->table_sorter->sort($rows, $col, $dir);
			}
		}
*/
		$rows = $CI->table_sorter->sort_multi_col($rows, $sorting_filter);
		if(!empty($paging_filter)) {
			$length = (int) $paging_filter['qf_rows_per_page'];
			$offset = (int) $paging_filter['qf_first_row'] - 1;
			$rows = array_slice($rows, $offset, $length);
		}
		return $rows;
	}
	
	// --------------------------------------------------------------------
	function get_parameters()
	{
		return $this->bound_calling_parameters;
	}

	// --------------------------------------------------------------------
	// return the column information that was cached from the last execute_sproc
	// that returned a rowset
		function get_column_info()
	{
		$CI =& get_instance();
		$CI->load->helper('cache');

		$col_info = array();
		// get cached values, if any
		$state = get_from_cache($this->col_info_storage_name);
		if($state) {
			$col_info = $state;
		}
		return $col_info;
	}
	// --------------------------------------------------------------------
	// return the number of rows that was cached from the last execute_sproc
	// that returned a rowset
	function get_total_rows()
	{
		$working_total = -1;

		$CI =& get_instance();
		$CI->load->helper('cache');

		// get cached values, if any
		$state = get_from_cache($this->total_rows_storage_name);
		if($state) {
			$working_total = $state;
		}
		return $working_total;
	}

	// --------------------------------------------------------------------
	function get_col_names()
	{
		$cols = array();
		$col_info = $this->get_column_info();
		foreach($col_info as $obj) {
			$cols[] = $obj->name;
		}
		return $cols;
	}

	// --------------------------------------------------------------------
	function get_sproc_args()
	{
		return $this->sproc_args;
	}

	// --------------------------------------------------------------------
	// return a list of fields for given sproc (minus the '<local>' fields)
	function get_sproc_fields()
	{
		$fields	= array();;
		foreach($this->sproc_args as $arg) {
			$field_name = $arg['field'];
			if($field_name != '<local>') {
				$fields[]	= $field_name;
			}
		}
		return $fields;		
	}

	// --------------------------------------------------------------------
	function get_error_text()
	{
		return $this->error_text;
	}

	// --------------------------------------------------------------------
	// get list of arguments for calling the stored procedure
	// based on configuration db definition and initialized from given param object
	function get_calling_args($parmObj)
	{
		$callingParams = new Bound_arguments();
		foreach($this->sproc_args as $arg) {
			$fn = ($arg['field'] == '<local>')?$arg['name']:$arg['field'];
	//		$b->fn = $fn; // ??
			if(isset($parmObj->$fn)) {
				$callingParams->$fn = $parmObj->$fn;
			} else {
				$callingParams->$fn = '';
			}
		}
		return $callingParams;
	}
	
	// --------------------------------------------------------------------
	private
	function get_sproc_arg_defs($config_name, $dbFileName)
	{
		$dbFilePath = $this->configDBFolder . $dbFileName;

		$dbh = new PDO("sqlite:$dbFilePath");
		if(!$dbh) throw new Exception('Could not connect to config database at '.$dbFilePath);

		// get list of tables in database
		$tbl_list = array();
		foreach ($dbh->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'", PDO::FETCH_ASSOC) as $row) {
			$tbl_list[] = $row['tbl_name'];
		}

		// set name of stored procedure (subject to override by an alias from the general parameter table)
		$this->sprocName = $config_name;

		// get parameters of interest from the general table
		foreach ($dbh->query("SELECT * FROM general_params", PDO::FETCH_ASSOC) as $row) {
			if($row['name'] == 'my_db_group') {
					$this->dbn = $row['value'];
			} else
			if(strpos($row['name'], $config_name) !== FALSE) { // (someday) require exact match for sproc name??
				// $config_name is alias for actual sproc name - change sproc name
				$this->sprocName = $row['value'];
			}
		}	
	
		// get definitions of arguments for stored procedure
		if(in_array('sproc_args', $tbl_list)) {
			$args = array();
			if(in_array('sproc_args', $tbl_list)) {
				$dbh = new PDO("sqlite:$dbFilePath");
		
				$sql = "select * from sproc_args where \"procedure\" = '$this->sprocName';";
				foreach ($dbh->query($sql, PDO::FETCH_ASSOC) as $row) {
					$args[] = array(
						'field' => $row['field'], 
						'name' => $row['name'], 
						'type' => $row['type'], 
						'dir' => $row['dir'], 
						'size' => $row['size']			
					);
				}
				$this->sproc_args = $args;
			}
		}
	}
	
	// --------------------------------------------------------------------
	private 
	function set_my_sproc_handler($hndlr_class) {
		$CI =& get_instance();
		$CI->load->library($hndlr_class, '', 'sprochndlr');
		$this->sproc_handler = $CI->sprochndlr;
	}
	
	// --------------------------------------------------------------------
	private 
	function _clear()
	{
	}

	// --------------------------------------------------------------------
	function get_config_name()
	{
		return $this->config_name;
	}

	// --------------------------------------------------------------------
	function get_sproc_name()
	{
		return $this->sprocName;
	}
	
	// --------------------------------------------------------------------
	function get_config_source()
	{
		return $this->config_source;
	}

	// --------------------------------------------------------------------
	function clear_cached_state()
	{
		$CI =& get_instance();
		$CI->load->helper('cache');
		clear_cache($this->total_rows_storage_name);
		clear_cache($this->col_info_storage_name);
	}
}
?>