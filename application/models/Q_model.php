<?php
// The primary function of this class is to build and execute an SQL query
// against one of the databases defined in the application/config/database file.
// It gets the basic components of the query from a config db as defined by the
// config_name and config_source.  It can then be augmented with values taken from 
// various user inputs in the form of filters (selection, paging, sorting). 
// This class also supplies certain definition information for use in building
// and using those filters.

/**
 * Track parts of the SQL query
 * @category Helper class 
 */
class Query_parts {
	
	/**
	 * Database name
	 * @var type 
	 */
	var $dbn = 'default';
	
	/**
	 * Table to retrieve data from
	 * @var type 
	 */
	var $table = '';
	
	/**
	 * Only used on detail reports (via detail_report_sproc); only used when detail_report_data_table is empty
	 * @var type 
	 */
	var $detail_sproc = '';
	
	/**
	 * Columns to show
	 * @var type 
	 */
	var $columns = '*';
	
	/**
	 * Query where clause info
	 * @var type 
	 */
	var $predicates = array(); 		// of Query_predicate
	
	/**
	 * User-defined list of column name and direction to sort on
	 * @var type 
	 */
	var $sorting_items = array(); 	// column => direction
	
	/**
	 * Paging information
	 * @var type 
	 */
	var $paging_items = array('first_row' => 1, 'rows_per_page' => 12);
	
	/**
	 * Default column and direction to sort on
	 * Multiple column names can be specified by separating them with a comma
	 * When using multiple columns, the same sort direction is applied to all of them
	 * @var type 
	 */
	var $sorting_default = array('col' => '', 'dir' => '');
}

/**
 * Track where clause items
 * @category Helper class 
 */
class Query_predicate {
	var $rel = 'AND';
	var $col;
	var $cmp;
	var $val;
}

/**
 * Keep track of the total rows returned by the query
 * @category Helper class 
 */
class CachedTotalRows {
	var $total = 0;
	var $base_sql = '';
}

/**
 * Class for building and executing an SQL query
 * against one of the databases defined in the application/config/database file
 */
class Q_model extends CI_Model {
	const col_info_storage_name_root = "col_info_";
	private $col_info_storage_name = "";
	//
	const total_rows_storage_name_root = "total_rows_";
	
	// Name under which to store the cached row count
	// Example name is total_rows_list_report_dataset
	private  $total_rows_storage_name = "";
	//
//	const display_cols_storage_name_root = "display_cols_";
//	const sql_storage_name_root = "base_sql_";
	
	private $config_name = '';
	private $config_source = '';
	private	$configDBFolder = "";
	
	/**
	 * Database-specific object to build SQL out of generic query parts
	 * @var type 
	 */
	private $sql_builder = NULL;
	
	/**
	 * SQL used by main query that returns rows
	 * @var type 
	 */
	private $main_sql = '';
	
	/**
	 * Parameters that will be used to build SQL
	 * Object of class Query_parts
	 * @var type 
	 */
	private $query_parts = NULL;

	/**
	 * Array of objects, one object per column
	 * Object has fields: name, type, max_length, primary_key
	 * @var type 
	 */
	private $result_column_info = NULL;
	
	/**
	 * Unix timestamp when the result column info was cached
	 * Data in $result_column_info is updated every 24 hours
	 * @var type 
	 */
	private $result_column_cachetime = NULL;
	
	const column_info_refresh_interval_minutes = 1440;
	
	/**
	 * Information from config DB about primary filter defined for config_name/config_source
	 * @var array
	 */
	private $primary_filter_specs = array();
	
	
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->configDBFolder = $this->config->item('model_config_path');
	}

	/**
	 * Get the basic query and filter definition information from a config db
	 * as specified by config_name and config_source
     * @param string $config_name Config type; na for list reports and detail reports, 
	 *                          but a query name like helper_inst_group_dstype when the source is ad_hoc_query
	 * @param string $config_source Data source, e.g. dataset, experiment, ad_hoc_query	 
	 * @return boolean
	 */
	function init($config_name, $config_source = "ad_hoc_query")
	{
		$this->config_name = $config_name;
		$this->config_source = $config_source;

		$this->col_info_storage_name = self::col_info_storage_name_root.$this->config_name.'_'.$this->config_source;
		$this->total_rows_storage_name = self::total_rows_storage_name_root.$this->config_name.'_'.$this->config_source;
		
		$dbFileName = $config_source . '.db';
		
		$this->_clear();
		$this->set_my_sql_builder('sql_mssql'); 	// (someday) pass in via argument (or constructor?) or do in config.php based on database type
		try {
			switch($config_name) {
				case '':
					break;
				case 'list_report':
					$this->get_list_report_query_specs_from_config_db($dbFileName);
					break;
				case 'detail_report':
					$this->get_detail_report_query_specs_from_config_db($dbFileName);
					break;
				case 'entry_page':
					$this->get_entry_page_query_specs_from_config_db($dbFileName);
					break;
				default:
					$this->get_query_specs_from_config_db($config_name, $dbFileName);
					break;
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
		return FALSE;
	}

	/**
	 * Clear the cached query information
	 */
	private 
	function _clear()
	{
		$this->query_parts = new Query_parts();
		$this->query_parts->dbn = '';
		$this->query_parts->table = '';
		$this->query_parts->columns = '*';
		$this->query_parts->predicates = array();		

		$this->primary_filter_specs = array();
	}
	
	/**
	 *  SQL will be built using a database-specific object - set it up here
	 * @param type $bldr_class
	 */
	private 
	function set_my_sql_builder($bldr_class) {
		$CI =& get_instance();
		$CI->load->library($bldr_class, '', 'sqlbldr');
		$this->sql_builder = $CI->sqlbldr;
	}

	/**
	 * Convert wildcards and special characters to SQL Server filters
	 * The presence of regex/glob style wildcard characters
	 *  will cause the defined column comparison to be
	 *  overridden by a 'LIKE' operator, with substitution of
	 *  SQL wildcards for regex/glob.
	 * A leading tilde will force an exact match
	 */
	function convert_wildcards()
	{
		for($i=0; $i<count($this->query_parts->predicates); $i++) {
			$p =& $this->query_parts->predicates[$i];
			
			if(strtolower($p->rel) == 'arg') {
				continue; // no wildards for arguements
			}
			
			// look for wildcard characters
			$match_blank = $p->val == '\b';
			$exact_match = (substr($p->val, 0, 1) == '~');
			$not_match = (substr($p->val, 0, 1) == ':');
			$regex_all = (strpos($p->val, '*') !== FALSE);
			$regex_one = (strpos($p->val, '?') !== FALSE);
			$sql_any   = (strpos($p->val, '%') !== FALSE);
						
			if($match_blank) {
				// Force match a blank
				$p->val = '';
				$p->cmp = "MatchesBlank";
			} else			
			if($exact_match || ($p->cmp === "MatchesText")) {
				// Force exact match
				// Remove the first character if it is a tilde or backtick (~ or `)
				$p->val = ltrim($p->val, '~`');
				$p->cmp = "MatchesText";
			} else
			if($not_match) {
				// Force does not contain text
				// Remove the first character if it is a colon
				$p->val = ltrim($p->val, ':');
				$p->cmp = "DoesNotContainText";
			} else
			if( $regex_all || $regex_one) {
				$p->cmp = 'wildcards';
			} else {
				$exceptions = array('MatchesText', 'MTx', 'MatchesTextOrBlank', 'MTxOB');
				if(!$sql_any and !in_array($p->cmp, $exceptions)) {
					// quote underscores in the absence of '%' or regex/glob wildcards
					$p->val = str_replace('_', '[_]', $p->val);	
				}		
			}
		}
	}

	/**
	 * Add one more item for building the query predicate ('WHERE' clause)
	 * @param string $rel
	 * @param string $col
	 * @param string $cmp
	 * @param string $val
	 */
	function add_predicate_item($rel, $col, $cmp, $val)
	{
		
		if($val != '') { 
			// (someday perhaps) reject if any field is empty, not just value field
			$p = new Query_predicate();
			$p->rel = $rel;
			$p->col = $col;
			$p->cmp = $cmp;
			// Check for encoded tabs and change them back to true tab characters
			// Also, trim whitespace
			$p->val = trim(str_replace('&#9;', "	", $val));
			$this->query_parts->predicates[] = $p;
		}
	}

	/**
	 * Add one more items to be used for building the 'Order by' clause
	 * @param type $col
	 * @param type $dir
	 */
	function add_sorting_item($col, $dir = '')
	{
		if($col) { // don't take malformed items
			$o = new stdClass();
			$o->col = $col;
			$d = strtoupper($dir);
			$o->dir = ($d == 'DESC')?$d:'ASC';
			$this->query_parts->sorting_items[] = $o;
		}
	}

	/**
	 * Replace the paging values
	 * @param type $first_row
	 * @param type $rows_per_page
	 */
	function add_paging_item($first_row, $rows_per_page)
	{
		if($first_row) { // don't take malformed items
			$this->query_parts->paging_items['first_row'] = $first_row;
			$this->query_parts->paging_items['rows_per_page'] = $rows_per_page;
		}
	}
	
	/**
	 * Construct the SQL query from component parts
	 * @param type $option
	 * @return type
	 */
	function get_sql($option = 'filtered_and_paged')
	{
		return $this->sqlbldr->build_query_sql($this->query_parts, $option);
	}

	// --------------------------------------------------------------------
	function get_item_sql($id)
	{
//		$id = 'xx';
		$spc = current($this->primary_filter_specs);
		$this->add_predicate_item('AND', $spc['col'], $spc['cmp'], $id);
		return $this->sqlbldr->build_query_sql($this->query_parts, 'filtered_only');
	}
	
	// --------------------------------------------------------------------
	function get_base_sql()
	{
		return $this->sql_builder->get_base_sql();
	}

	// --------------------------------------------------------------------
	function get_main_sql()
	{
		return $this->main_sql;
	}

	// --------------------------------------------------------------------
	function get_query_parts()
	{
		return $this->query_parts;
	}
	
	// --------------------------------------------------------------------
	function set_table($table)
	{
		$this->query_parts->table = $table;
	}

	// --------------------------------------------------------------------
	function set_columns($columns)
	{
		$this->query_parts->columns = $columns;
	}

	/**
	 * Return single row for given ID using first defined filter
	 * Used to retrieve data for a detail report
	 * @param string $id
	 * @return type
	 * @throws exception
	 */
	function get_item($id)
	{
		if(empty($this->primary_filter_specs)) { 
			throw new exception('no primary id column defined');                     
		}
		
		if (empty($this->query_parts->table) && !empty($this->query_parts->detail_sproc))
		{
			return $this->get_data_row_from_sproc($id);
		}
		else 
		{
			$spc = current($this->primary_filter_specs);
			$this->add_predicate_item('AND', $spc['col'], $spc['cmp'], $id);
		
			$query = $this->get_rows('filtered_only');

			// get single row from results
			return $query->row_array();
		}
		
	}
	
	/**
	 * Retrieve data for a list report when $option is 'filtered_and_paged'
	 * Retrieve a single result for a detail report when $option is 'filtered_only'
	 * @param type $option Can be 'filtered_and_paged' or 'filtered_only'
	 * @return type
	 */
	function get_rows($option = 'filtered_and_paged')
	{
		$this->assure_sorting($option);

		$this->main_sql = $this->sqlbldr->build_query_sql($this->query_parts, $option);

		$my_db = $this->get_db_object($this->query_parts->dbn);
		$query = $my_db->query($this->main_sql);
		//		$this->set_col_info_data($query->field_data());
		return $query;
// $query->result() // array of objects		
// $query->result_array() // array of arrays
// $query->free_result();
	}

	/**
	 * Ported from get_data_rows_from_sproc in Param_report.php
	 * Returns the first row of data returned by the stored procedure
	 * @param type $id
	 * @return type
	 * @throws exception Thrown if there is an error or if the SP returns a non-zero value
	 */
	function get_data_row_from_sproc($id)
	{
		$CI = &get_instance();
		
		$calling_params = new stdClass();

		// When calling a stored procedure from a detail report, we do not allow for passing custom values for stored procedure parameters
		// If you want to do that, use a list-report that is backed by a stored procedure
		// (see, for example, predefined_analysis_datasets or requested_run_factors)
		// 
		// The default parameter names are id, mode, callingUser, and message
		// However, the stored procedure doesn't have to have those parameters.
		// Use the sproc_args table in the config DB to specify the actual parameters
		
		$calling_params->id = $id;
		$calling_params->mode = 'Get';
		$calling_params->callingUser = '';		// get_user();

		try {
			// Call the stored procedure		
			$ok = $CI->cu->load_mod('s_model', 'sproc_model',$this->config_name, $this->config_source);
			if(!$ok) { 
				throw new exception($CI->sproc_model->get_error_text());                             
			}

			$success = $CI->sproc_model->execute_sproc($calling_params);
			if(!$success) { 
				throw new exception($CI->sproc_model->get_error_text());                             
			}

			$rows = $CI->sproc_model->get_rows();
			
			if (empty($rows)) {
				// No results
				// echo "<div id='data_message' >No rows found</div>";
				return NULL;
			}
			
			return $rows[0];
			
		} catch (Exception $e) {
			$message = $e->getMessage();
			throw new exception($message);
		}

	
	}

	/**
	 * Obtain the database object for the given database group
	 * @param mixed	$dbGroupName DB Group name, typically default or broker, but sometimes 
	 *                           package, capture, prism_ifc, prism_rpt, ontology, or manager_control
	 *                           If empty, the active group is used (defined by $active_group)
	 * @throws Exception
	 */
	private
	function get_db_object($dbGroupName) {	
		
		$CI =& get_instance();
		
		// Connect to the database
		// Retry the connection up to 5 times
		$connectionRetriesRemaining = 5;

		// The initial delay when retrying is 250 msec
		// This is doubled to 500 msec, then 1000, 2000, & 4000 msec if we end up retrying the connection
		$connectionSleepDelayMsec = 250;

		while ($connectionRetriesRemaining > 0) {
			try {
				$my_db = $CI->load->database($dbGroupName, TRUE, TRUE);
				
				if ($my_db === false) {
					// $CI->load->database() normally returns a database object
					// But if an error occurs, it returns FALSE
					// Retry establishing the connection
					throw new Exception('$CI->load->database returned false in Q_model');
				} else {
					if ($my_db->conn_id === false) {
						// $my_db->conn_id is normally an object
						// But if an error occurs, it is FALSE
						// Retry establishing the connection
						throw new Exception('$my_db->conn_id returned false in Q_model');
					}
				}

				// Exit the while loop
				break;
				
			} catch (Exception $ex) {
				$errorMessage = $ex->getMessage();
				
				$groupNameForLog = 'default';
				if (!empty($dbGroupName)) {
					$groupNameForLog = $dbGroupName;
				}

				$logMessage = "Exception connecting to the $groupNameForLog DB: $errorMessage";
				
				log_message('error', $logMessage);
				$connectionRetriesRemaining--;
				if ($connectionRetriesRemaining > 0) {
					log_message('error', "Retrying connection to the $groupNameForLog DB in $connectionSleepDelayMsec msec");
					usleep($connectionSleepDelayMsec * 1000);
					$connectionSleepDelayMsec *= 2;
				} else {
					throw new Exception("Connection to the $groupNameForLog DB failed: $errorMessage");
				}
			}

		}
		
		return $my_db;

	}

	/**
	 * Make sure there is at least one valid sorting column if option includes sorting
	 * @param type $option
	 * @throws exception
	 */
	private
	function assure_sorting($option)
	{
		if($option == 'filtered_and_paged' or $option == 'filtered_and_sorted') {
			// only need to dig in if there aren't any sorting items already
			if(empty($this->query_parts->sorting_items)) {
				// Use default sorting column or first column
				$col = $this->query_parts->sorting_default['col'];
								
				// use default sorting direction or ASC
				$dir = $this->query_parts->sorting_default['dir'];
				if($dir) {
					// Assure that the sort direction is ASC or DESC
					// Will auto-update desc or Descending to DESC; anything else is ASC
					if ($this->startsWith(strtoupper($dir), "DESC")) {
						$dir = "DESC";
					} else {
						$dir = "ASC";
					}
				} else {
					$dir = 'ASC';
				}

				if($col) {
					// Default column is defined
					// It may contain multiple column names, separated by a comma. If so, append each separately
					$colNames = explode(",", $col);
					foreach($colNames as $colName) {
						$this->add_sorting_item(trim($colName), $dir);
					}
				} else {
					// Default column to sort on is not defined in the Model Config DB
					// Sort on the first column
					$col_info = $this->get_column_info();
					if($col_info) {
						$col = $col_info[0]->name;
					} else {
						throw new exception('cannot find default sorting row for "filitered_and_paged" ');
					}
					$this->add_sorting_item($col, $dir);
				}
				
			}
		}
	}
	
	/**
	 * Return the number of rows that would be generated by the query
	 * without paging contraints (needed to make paging controls)
	 * 
	 * Always try to use cached values if present 
	 * (external code will call clear_cached_total_rows to force reload from DB)
	 * If no cache available, or if base sql in cache does not match current base sql, 
	 * reload total from database and cache it
	 * @return type
	 * @throws Exception
	 */
	function get_total_rows()
	{
		$working_total = -1;

		// need to get current base sql to compare with cached version
		$sql = $this->sqlbldr->build_query_sql($this->query_parts, 'count_only');
		$base_sql = $this->get_base_sql();

		// Get cached values, if any.  
		// $state object has properties base_sql and total.  
		// Example base_sql:
		// " FROM V_Dataset_List_Report_2" or
		// " FROM V_Data_Package_Aggregation_List_Report WHERE [Data_Package_ID] = 194'
		$state = get_from_cache($this->total_rows_storage_name);
		if($state) {
			if($state->base_sql == $base_sql) {
				$working_total = $state->total;
			}
		}
		
		if($working_total < 0) {
			// get total from database					
			$my_db = $this->get_db_object($this->query_parts->dbn);			
			$query = $my_db->query($sql);
			if(!$query) {
				throw new Exception("Error getting total row count from database");
			}
                        
	 		if ($query->num_rows() == 0) {
	 			throw new Exception("Total count row was not returned");
			}
                        
			$row = $query->row();
			$query->free_result();
			$working_total = $row->numrows;

			// Cache the row count for the given base_sql
			$state = new CachedTotalRows();
			$state->total = $working_total;
			$state->base_sql = $base_sql;
			save_to_cache($this->total_rows_storage_name, $state);
		}
		return $working_total;
	}
	
	// --------------------------------------------------------------------
	function get_cached_total_rows()
	{
		return get_from_cache($this->total_rows_storage_name);
	}
	
	// --------------------------------------------------------------------
	function clear_cached_total_rows()
	{
		$CI =& get_instance();
		$CI->load->helper('cache');
		clear_cache($this->total_rows_storage_name);
	}

	// --------------------------------------------------------------------
	function clear_cached_state()
	{
		$CI =& get_instance();
		$CI->load->helper('cache');
		clear_cache($this->total_rows_storage_name);
		clear_cache($this->col_info_storage_name);
	}

	/**
	 * Get information about columns that would be generated by current query parts,
	 * either from cache or by running a single-row query against database
	 * @return type
	 */
	function get_column_info()
	{
		
		$forceRefresh = false;
		if (!is_null($this->result_column_cachetime) && 
		    (time() - $this->result_column_cachetime) / 60.0 > self::column_info_refresh_interval_minutes) {
			$forceRefresh=true;
		}
			
		if(!$this->result_column_info || $forceRefresh) {
			$CI =& get_instance();
			$CI->load->helper('cache');
			$state = get_from_cache($this->col_info_storage_name);
			if($state) {
				$this->result_column_info = $state;
			} else {
				$state = $this->get_col_data();
				if($state) {
					$this->set_col_info_data($state);
				}
			}
			
			$this->result_column_cachetime = time();
		}
		return $this->result_column_info;
	}

	// --------------------------------------------------------------------
	function get_column_info_cache_name()
	{
		return $this->col_info_storage_name;
	}
	
	// --------------------------------------------------------------------
	function get_column_info_cache_data()
	{
		$CI =& get_instance();
		$CI->load->helper('cache');

		return get_from_cache($this->col_info_storage_name);
	}
	
	// --------------------------------------------------------------------
	private
	function set_col_info_data($state)
	{
		$CI =& get_instance();
		$CI->load->helper('cache');
		
		$this->result_column_info = $state;
		save_to_cache($this->col_info_storage_name, $state);
	}

	/**
	 * Get a single row from database and remember the column information
	 * @return type
	 */
	private
	function get_col_data()
	{
		$sql = $this->sqlbldr->build_query_sql($this->query_parts, 'column_data_only');

		$my_db = $this->get_db_object($this->query_parts->dbn);
		$query = $my_db->query($sql);
		$result_column_info = $query->field_data();
		// $query->free_result();
		return $result_column_info;
	}
	
	/**
	 * Get the column names
	 * @return mixed Array of names
	 */
	function get_col_names()
	{
		$cols = array();
		$col_info = $this->get_column_info();
		foreach($col_info as $obj) {
			$cols[] = $obj->name;
		}
		return $cols;
	}

	/**
	 * Get the data type for the given column
	 * @param type $col Column Name
	 * @return string
	 */
	function get_column_data_type($col)
	{
		$type = '??';
		$col_info = $this->get_column_info();
		foreach($col_info as $obj) {
			if($col == $obj->name) {
				$type = $obj->type;
				break;
			}
		}
		return $type;
	}
	
	/**
	 * Load the query specs from table utility_queries in the config DB
	 * @param string $config_name
	 * @param string $dbFileName
	 * @throws Exception
	 */
	private 
	function get_query_specs_from_config_db($config_name, $dbFileName)
	{
		$dbFilePath = $this->configDBFolder . $dbFileName;
		$dbh = new PDO("sqlite:$dbFilePath");
		if(!$dbh) { 
			throw new Exception('Could not connect to config database at '.$dbFilePath);                     
		}

		$sth = $dbh->prepare("SELECT * FROM utility_queries WHERE name='$config_name'");
		$sth->execute();
		$obj = $sth->fetch(PDO::FETCH_OBJ);
		if($obj === FALSE) { 
			throw new Exception('Could not find query specs');                     
		}
		
		$this->query_parts->dbn = $obj->db;
		$this->query_parts->table = $obj->table;
		$this->query_parts->columns = $obj->columns;
		$filters = (isset($obj->filters) && $obj->filters != '')?json_decode($obj->filters, TRUE):array();
		$this->primary_filter_specs = array();
		foreach($filters as $col => $cmp) {
			$name = "pf_".str_replace(' ', '_', strtolower($col));
			$a = array();
			$a['label'] = $col;
			$a['col'] = $col;
			$a['cmp'] = $cmp;
			$a['value'] = '';
			$this->primary_filter_specs[$name] = $a;
		}
		$sorting = (isset($obj->sorting) && $obj->sorting != '')?json_decode($obj->sorting, TRUE):array();
		if(!empty($sorting)) {
			$this->query_parts->sorting_default= $sorting;
		}
	}

	/**
	 * Get the list report query specs from tables general_params, list_report_primary_filter, and primary_filter_choosers
	 * @param type $dbFileName
	 * @throws Exception
	 */
	private 
	function get_list_report_query_specs_from_config_db($dbFileName)
	{
		$dbFilePath = $this->configDBFolder . $dbFileName;

		$dbh = new PDO("sqlite:$dbFilePath");
		if(!$dbh) {
			throw new Exception('Could not connect to config database at '.$dbFilePath);
		}

		// get list of tables in database
		$tbl_list = array();
		foreach ($dbh->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'", PDO::FETCH_ASSOC) as $row) {
			$tbl_list[] = $row['tbl_name'];
		}
		
		foreach ($dbh->query("SELECT * FROM general_params", PDO::FETCH_ASSOC) as $row) {
			switch($row['name']) {
				case 'my_db_group':
					$this->query_parts->dbn = $row['value'];
					break;
				case 'list_report_data_table':
					$this->query_parts->table = $row['value'];
					break;
				case 'list_report_data_cols':
					$this->query_parts->columns = $row['value'];
					break;
				case 'list_report_data_sort_col':
					$this->query_parts->sorting_default['col'] = $row['value'];
					break;
				case 'list_report_data_sort_dir':
					$sortDir = $row['value'];
					if (substr(strtolower($sortDir), 0, 3) === "asc") {
						$this->query_parts->sorting_default['dir'] = 'ASC';
					} else {
						$this->query_parts->sorting_default['dir'] = 'DESC';
					}
					break;
			}
		}

		if(in_array('list_report_primary_filter', $tbl_list)) {
			$this->primary_filter_specs = array();
			foreach ($dbh->query("SELECT * FROM list_report_primary_filter", PDO::FETCH_ASSOC) as $row) {
				$a = array();
				$a['label'] = $row['label'];
				$a['size'] = $row['size'];
				$a['value'] = $row['value'];
				$a['col'] = $row['col'];
				$a['cmp'] = $row['cmp'];
				$a['type'] = $row['type'];
				$a['maxlength'] = $row['maxlength'];
				$a['rows'] = $row['rows'];
				$a['cols'] = $row['cols'];
				$this->primary_filter_specs[$row['name']] = $a;
			}
		}
		if(in_array('primary_filter_choosers', $tbl_list)) {
			$fl = array();
			foreach ($dbh->query("SELECT * FROM primary_filter_choosers", PDO::FETCH_ASSOC) as $row) {
				$a = array();
				$a['type'] = $row['type'];
				$a['PickListName'] = $row['PickListName'];
				$a['Target'] = $row['Target'];
				$a['XRef'] = $row['XRef'];
				$a['Delimiter'] = $row['Delimiter'];
				$fl[$row['field']][] = $a;
			}
			foreach($fl as $fn => $ch) {
				if(count($ch) == 1) {
					$this->primary_filter_specs[$fn]['chooser_list'] = array($ch[0]);				
				} else {
					$this->primary_filter_specs[$fn]['chooser_list'] = $ch;				
				}
			}
		}
				
	}

	/**
	 * Get the detail report query specs from the general_params table
	 * @param type $dbFileName
	 * @throws Exception
	 */
	private 
	function get_detail_report_query_specs_from_config_db($dbFileName)
	{
		$dbFilePath = $this->configDBFolder . $dbFileName;

		$dbh = new PDO("sqlite:$dbFilePath");
		if(!$dbh) { 
			throw new Exception('Could not connect to config database at '.$dbFilePath);                     
		}

		foreach ($dbh->query("SELECT * FROM general_params", PDO::FETCH_ASSOC) as $row) {
			switch($row['name']) {
				case 'my_db_group':
					$this->query_parts->dbn = $row['value'];
					break;
				case 'detail_report_data_table':
					$this->query_parts->table = $row['value'];
					break;
				case 'detail_report_sproc':
					$this->query_parts->detail_sproc = $row['value'];
					break;
				case 'detail_report_data_cols':
					$this->query_parts->columns = $row['value'];
					break;
				case 'detail_report_data_id_col':
					$col = $row['value'];
					// $name = "pf_".str_replace(' ', '_', strtolower($col));
					$a = array();
					$a['col'] = $col;
					$a['cmp'] = 'MatchesText';// 'Equals'; // 'MatchesText'?
					$a['label'] = $col;
					$this->primary_filter_specs[$row['name']] = $a;
					break;
			}
		}	
	}

	/**
	 * Get the entry page query specs from tables the general_params table
	 * @param type $dbFileName
	 * @throws Exception
	 */
	private 
	function get_entry_page_query_specs_from_config_db($dbFileName)
	{
		$dbFilePath = $this->configDBFolder . $dbFileName;

		$dbh = new PDO("sqlite:$dbFilePath");
		if(!$dbh) {
			throw new Exception('Could not connect to config database at '.$dbFilePath);
		}

		foreach ($dbh->query("SELECT * FROM general_params", PDO::FETCH_ASSOC) as $row) {
			switch($row['name']) {
				case 'my_db_group':
					$this->query_parts->dbn = $row['value'];
					break;
				case 'entry_page_data_table':
					$this->query_parts->table = $row['value'];
					break;
				case 'entry_page_data_cols':
					$this->query_parts->columns = $row['value'];
					break;
				case 'entry_page_data_id_col':
					$col = $row['value'];
					// $name = "pf_".str_replace(' ', '_', strtolower($col));
					$a = array();
					$a['col'] = $col;
					$a['cmp'] = 'MatchesText'; // 'MatchesText'? 'Equals'?
					$a['label'] = $col;
					$this->primary_filter_specs[$row['name']] = $a;
					break;
			}
		}	
	}

	// --------------------------------------------------------------------
	function get_config_name()
	{
		return $this->config_name;
	}

	// --------------------------------------------------------------------
	function get_config_source()
	{
		return $this->config_source;
	}

	// --------------------------------------------------------------------
	// stuff for query filters
	// --------------------------------------------------------------------

	/**
	 * Information from config DB about primary filter defined for config_name/config_source
	 * @return array
	 */
	function get_primary_filter_specs()
	{
		return $this->primary_filter_specs;
	}
	
	/**
	 * Get the allowed comparisons for the given data type
	 * @param string $type
	 * @return mixed
	 */
	function get_allowed_comparisons_for_type($type)
	{
		return $this->sql_builder->get_allowed_comparisons_for_type($type);
	}

	/**
	 * Allowed query predicate operators: AND and OR
	 * @return mixed
	 */
	function get_allowed_rel_values()
	{
		return array("AND" => "AND","OR" => "OR");
	}

	function startsWith($haystack, $needle)
	{
		 $length = strlen($needle);
		 return (substr($haystack, 0, $length) === $needle);
	}	
}
