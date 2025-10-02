<?php
namespace App\Models;

use App\Libraries\Query_parts;
use App\Libraries\Query_predicate;
use CodeIgniter\Model;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\SQLite3\Connection;

// The primary function of this class is to build and execute an SQL query
// against one of the databases defined in the app/Config/database file.

// It gets the basic components of the query from a config db as defined by the
// config_name and config_source.  It can then be augmented with values taken from
// various user inputs in the form of filters (selection, paging, sorting).

// This class also supplies certain definition information for use in building
// and using those filters.

/**
 * Keep track of the total rows returned by the query
 * @category Helper class
 */
class CachedTotalRows {

    public $total = 0;
    public $base_sql = '';
    public $cache_time = 0;

}

/**
 * Class for building and executing an SQL query
 * against one of the databases defined in the app/Config/database file
 */
class Q_model extends Model {

    const col_info_storage_name_root = "col_info_";

    private $col_info_storage_name = "";

    //
    const total_rows_storage_name_root = "total_rows_";

    // Name under which to store the cached row count
    // Example name is total_rows_list_report_dataset
    private $total_rows_storage_name = "";
    //
//  const display_cols_storage_name_root = "display_cols_";
//  const sql_storage_name_root = "base_sql_";

    private $config_name = '';
    private $config_source = '';
    private $configDBPath = "";

    /**
     * Database-specific object to build SQL out of generic query parts
     * @var \App\Libraries\Sql_base
     */
    private \App\Libraries\Sql_base $sql_builder;

    /**
     * SQL used by main query that returns rows
     * @var string
     */
    private string $main_sql = '';

    /**
     * Parameters that will be used to build SQL
     * Object of class Query_parts
     * @var Query_parts
     */
    private Query_parts $query_parts;

    /**
     * Array of objects, one object per column
     * Object has fields: name, type, max_length, primary_key
     * @var array|null
     */
    private ?array $result_column_info = null;

    /**
     * Unix timestamp when the result column info was cached
     * Data in $result_column_info is updated every 24 hours
     * @var int|null
     */
    private ?int $result_column_cachetime = null;

    const column_info_refresh_interval_minutes = 1440;

    /**
     * Information from config DB about primary filter defined for config_name/config_source
     * @var array
     */
    private $primary_filter_specs = array();

    // --------------------------------------------------------------------
    function __construct() {
        // Call the Model constructor
        parent::__construct();

        // Include the String operations methods
        helper('string');
    }

    /**
     * Get the basic query and filter definition information from a config db
     * as specified by config_name and config_source
     * @param string $config_name Config type; na for list reports and detail reports,
     *                          but a query name like helper_inst_group_dstype when the source is ad_hoc_query
     * @param string $config_source Data source, e.g. dataset, experiment, ad_hoc_query
     * @return bool
     */
    function init($config_name, $config_source = "ad_hoc_query") {
        $this->config_name = $config_name;
        $this->config_source = $config_source;

        $this->col_info_storage_name = self::col_info_storage_name_root . $this->config_name . '_' . $this->config_source;
        $this->total_rows_storage_name = self::total_rows_storage_name_root . $this->config_name . '_' . $this->config_source;

        $dbFileName = $config_source . '.db';

        helper(['config_db','string', 'database']);
        $this->configDBPath = get_model_config_db_path($dbFileName)->path;

        $this->_clear();

        try {
            switch ($config_name) {
                case '':
                    break;
                case 'list_report':
                    $this->get_list_report_query_specs_from_config_db();
                    break;
                case 'detail_report':
                    $this->get_detail_report_query_specs_from_config_db();
                    break;
                case 'entry_page':
                    $this->get_entry_page_query_specs_from_config_db();
                    break;
                default:
                    $this->get_query_specs_from_config_db($config_name);
                    break;
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            log_message('error', "Exception getting Q_model query specs: (config name $config_name): $errorMessage");
            throw new \Exception("Failure getting Q_model query specs: (config name $config_name): $errorMessage");
        }

        // Connect to the database
        // Retry the connection up to 5 times
        $connectionRetriesRemaining = 5;

        // The initial delay when retrying is 250 msec
        // This is doubled to 500 msec, then 1000, 2000, & 4000 msec if we end up retrying the connection
        $connectionSleepDelayMsec = 250;

        $my_db = null;
        while ($connectionRetriesRemaining > 0) {
            try {
                // Try to establish a connection to the database. Either returns BaseConnection object, or throws an exception
                $my_db = \Config\Database::connect(GetNullIfBlank($this->query_parts->dbn));
                update_search_path($my_db);

                // Many functions check for and initialize the DB connection if not there,
                // but that leaves connection issues popping up in random places
                if (empty($my_db->connID)) {
                    // $my_db->connID is normally an object
                    // But if an error occurs or it disconnects, it is false/empty
                    // Try initializing first
                    $my_db->initialize();
                }

                if ($my_db->connID === false) {
                    // $my_db->connID is normally an object
                    // But if an error occurs, it is false
                    // Retry establishing the connection
                    throw new \Exception('$my_db->connID returned false in S_model');
                }

                // Exit the while loop
                break;

            } catch (\Exception $ex) {
                $errorMessage = $ex->getMessage();
                log_message('error', "Exception connecting to DB group '{$this->query_parts->dbn}' (config name $config_name): $errorMessage");
                $connectionRetriesRemaining--;
                if ($connectionRetriesRemaining > 0) {
                    log_message('error', "Retrying connection to '{$this->query_parts->dbn}' in $connectionSleepDelayMsec msec");
                    usleep($connectionSleepDelayMsec * 1000);
                    $connectionSleepDelayMsec *= 2;
                } else {
                    throw new \Exception("Connection to DB group '{$this->query_parts->dbn}' failed: $errorMessage");
                }
            }
        }

        $this->set_my_sql_builder("Sql_" . strtolower($my_db->DBDriver));

        return false;
    }

    /**
     * Clear the cached query information
     */
    private function _clear() {
        $this->query_parts = new Query_parts();
        $this->query_parts->dbn = 'default';
        $this->query_parts->table = '';
        $this->query_parts->columns = '*';
        $this->query_parts->predicates = array();

        $this->primary_filter_specs = array();
    }

    /**
     *  SQL will be built using a database-specific object - set it up here
     * @param string $bldr_class
     */
    private function set_my_sql_builder(string $bldr_class) {
        $sqlBuilder = "\\App\\Libraries\\$bldr_class";
        $this->sql_builder = new $sqlBuilder();
    }

    /**
     * Convert wildcards and special characters to SQL Server filters
     * The presence of regex/glob style wildcard characters
     *  will cause the defined column comparison to be
     *  overridden by a 'LIKE' operator, with substitution of
     *  SQL wildcards for regex/glob.
     * A leading tilde will force an exact match
     */
    function convert_wildcards() {
        for ($i = 0; $i < count($this->query_parts->predicates); $i++) {
            $p = & $this->query_parts->predicates[$i];

            if (strtolower($p->rel) == 'arg') {
                continue; // no wildards for arguements
            }

            // Look for wildcard characters
            $match_blank = $p->val == '\b';
            $exact_match = (substr($p->val, 0, 1) == '~');
            $not_match = (substr($p->val, 0, 1) == ':');
            $regex_all = (strpos($p->val, '*') !== false);
            $regex_one = (strpos($p->val, '?') !== false);
            $sql_any = (strpos($p->val, '%') !== false);

            if ($match_blank) {
                // Force match a blank
                $p->val = '';
                $p->cmp = "MatchesBlank";
            } else
            if ($exact_match || ($p->cmp === "MatchesText") || ($p->cmp === "MTx")) {
                // Force exact match
                // Remove the first character if it is a tilde or backtick (~ or `)
                $p->val = ltrim($p->val, '~`');
                $p->cmp = "MatchesText";
            } else
            if ($not_match) {
                // Force does not contain text
                // Remove the first character if it is a colon
                $p->val = ltrim($p->val, ':');
                $p->cmp = "DoesNotContainText";
            } else
            if ($regex_all || $regex_one) {
                $p->cmp = 'wildcards';
            } else {
                $exceptions = array('MatchesText', 'MTx', 'MatchesTextOrBlank', 'MTxOB');
                if (!$sql_any && !in_array($p->cmp, $exceptions)) {
                    // Surround underscores with square brackets (in the absence of '%' or regex/glob wildcards)
                    // However, leave \_ and [_] as-is

                    // echo "p->val (before): " . $p->val . '<br>';

                    $p->val = preg_replace("/([^\\[])_/i", '$1[_]', $p->val);

                    // echo "p->val (after): " . $p->val . '<br>';
                }
            }
        }
    }

    /**
     * Add one more item for building the query predicate ('WHERE' clause)
     * @param string $rel Boolean operator (AND or OR)
     * @param string $col Column name to filter on
     * @param string $cmp Comparison mode (ContainsText, StartsWithText, GreaterThan, etc.)
     * @param string $val Value to filter on
     */
    function add_predicate_item($rel, $col, $cmp, $val) {

        if ($val != '') {
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
     * @param string $col
     * @param string $dir
     */
    function add_sorting_item(string $col, string $dir = '') {
        if ($col) { // don't take malformed items
            $o = new \stdClass();
            $o->col = $col;
            $d = strtoupper($dir);
            $o->dir = ($d == 'DESC') ? $d : 'ASC';
            $this->query_parts->sorting_items[] = $o;
        }
    }

    /**
     * Replace the paging values
     * @param int $first_row
     * @param int $rows_per_page
     */
    function add_paging_item($first_row, $rows_per_page) {
        if ($first_row) { // don't take malformed items
            $this->query_parts->paging_items['first_row'] = $first_row;
            $this->query_parts->paging_items['rows_per_page'] = $rows_per_page;
        }
    }

    /**
     * Construct the SQL query from component parts
     * @param string $option, which can be 'filtered_only', 'filtered_and_paged', 'filtered_and_sorted', or 'count_only'
     * @return string
     */
    function get_sql(string $option = 'filtered_and_paged'): string {
        return $this->sql_builder->build_query_sql($this->query_parts, $option);
    }

    // --------------------------------------------------------------------
    function get_item_sql($id) {
//      $id = 'xx';
        $spc = current($this->primary_filter_specs);
        $this->add_predicate_item('AND', $spc['col'], $spc['cmp'], $id);
        return $this->sql_builder->build_query_sql($this->query_parts, 'filtered_only');
    }

    // --------------------------------------------------------------------
    function get_base_sql() {
        return $this->sql_builder->get_base_sql();
    }

    // --------------------------------------------------------------------
    function get_main_sql() {
        return $this->main_sql;
    }

    // --------------------------------------------------------------------
    function get_query_parts() {
        return $this->query_parts;
    }

    // --------------------------------------------------------------------
    function set_table($table) {
        $this->query_parts->table = $table;
    }

    // --------------------------------------------------------------------
    function set_columns($columns) {
        $this->query_parts->columns = $columns;
    }

    /**
     * Return single row for given ID using first defined filter
     * Used to retrieve data for a detail report
     * @param string $id
     * @param \App\Controllers\BaseController $controller
     * @return array
     * @throws \Exception
     */
    function get_item($id, \App\Controllers\BaseController $controller): array {
        if (empty($this->primary_filter_specs)) {
            throw new \Exception('no primary id column defined; update general_params to include detail_report_data_id_col');
        }

        if (empty($this->query_parts->table) && !empty($this->query_parts->detail_sproc)) {
            return $this->get_data_row_from_sproc($id, $controller);
        } else {
            // Primary_filter_specs is populated in get_detail_report_query_specs_from_config_db
            $spc = current($this->primary_filter_specs);
            $this->add_predicate_item('AND', $spc['col'], $spc['cmp'], $id);

            $query = $this->get_rows('filtered_only');

            // Get single row from results
            $data = $query->getRowArray();
            if (is_null($data))
            {
                $data = array();
            }

            return $data;
        }
    }

    /**
     * Retrieve data for a list report when $option is 'filtered_and_paged'
     * Retrieve a single result for a detail report when $option is 'filtered_only'
     * @param string $option Can be 'filtered_and_paged' or 'filtered_only'
     * @return object
     */
    function get_rows(string $option = 'filtered_and_paged') {
        $this->assure_sorting($option);

        $this->main_sql = $this->sql_builder->build_query_sql($this->query_parts, $option);

        $my_db = $this->get_db_object($this->query_parts->dbn);
        $query = $my_db->query($this->main_sql);
        //      $this->set_col_info_data($query->getFieldData());
        return $query;
// $query->getResult() // array of objects
// $query->getResultArray() // array of arrays
// $query->freeResult();
    }

    /**
     * Ported from get_data_rows_from_sproc in Param_report.php
     * Returns the first row of data returned by the stored procedure
     * @param string $id
     * @param \App\Controllers\BaseController $controller
     * @return mixed
     * @throws \Exception Thrown if there is an error or if the SP returns a non-zero value
     */
    function get_data_row_from_sproc($id, \App\Controllers\BaseController $controller) {
        $calling_params = new \stdClass();

        // When calling a stored procedure from a detail report, we do not allow for passing custom values for stored procedure parameters
        // If you want to do that, use a list-report that is backed by a stored procedure
        // (see, for example, predefined_analysis_datasets or requested_run_factors)
        //
        // The default parameter names are id, mode, callingUser, and message
        // However, the stored procedure doesn't have to have those parameters.
        // Use the sproc_args table in the config DB to specify the actual parameters

        $calling_params->id = $id;
        $calling_params->mode = 'Get';
        $calling_params->callingUser = '';      // get_user();

        try {
            // Call the stored procedure
            $ok = $controller->loadSprocModel($this->config_name, $this->config_source);
            if (!$ok) {
                throw new \Exception($controller->sproc_model->get_error_text());
            }

            $success = $controller->sproc_model->execute_sproc($calling_params);
            if (!$success) {
                throw new \Exception($controller->sproc_model->get_error_text());
            }

            $rows = $controller->sproc_model->get_rows();

            if (empty($rows)) {
                // No results
                // echo "<div id='data_message' >No rows found</div>";
                return null;
            }

            return $rows[0];
        } catch (\Exception $e) {
            $message = $e->getMessage();
            throw new \Exception($message);
        }
    }

    /**
     * Obtain the database object for the given database group
     * @param string $dbGroupName DB Group name, typically default or broker, but sometimes
     *                            package, capture, prism_ifc, prism_rpt, ontology, or manager_control
     *                            If empty, the active group is used (defined by $active_group)
     * @throws \Exception
     */
    private function get_db_object(string $dbGroupName) {
        // Connect to the database
        // Retry the connection up to 5 times
        $connectionRetriesRemaining = 5;

        // The initial delay when retrying is 250 msec
        // This is doubled to 500 msec, then 1000, 2000, & 4000 msec if we end up retrying the connection
        $connectionSleepDelayMsec = 250;

        helper(['string', 'database']);

        $my_db = null;
        while ($connectionRetriesRemaining > 0) {
            try {
                // Try to establish a connection to the database. Either returns BaseConnection object, or throws an exception
                $my_db = \Config\Database::connect(GetNullIfBlank($dbGroupName));
                update_search_path($my_db);

                // Many functions check for and initialize the DB connection if not there,
                // but that leaves connection issues popping up in random places
                if (empty($my_db->connID)) {
                    // $my_db->connID is normally an object
                    // But if an error occurs or it disconnects, it is false/empty
                    // Try initializing first
                    $my_db->initialize();
                }

                if ($my_db->connID === false) {
                    // $my_db->connID is normally an object
                    // But if an error occurs, it is false
                    // Retry establishing the connection
                    throw new \Exception('$my_db->connID returned false in Q_model');
                }

                // Exit the while loop
                break;
            } catch (\Exception $ex) {
                $errorMessage = $ex->getMessage();

                $groupNameForLog = 'default';
                if (!empty($dbGroupName)) {
                    $groupNameForLog = $dbGroupName;
                }

                $logMessage = "Exception connecting to the '$groupNameForLog' DB: $errorMessage";

                log_message('error', $logMessage);
                $connectionRetriesRemaining--;
                if ($connectionRetriesRemaining > 0) {
                    log_message('error', "Retrying connection to the '$groupNameForLog' DB in $connectionSleepDelayMsec msec");
                    usleep($connectionSleepDelayMsec * 1000);
                    $connectionSleepDelayMsec *= 2;
                } else {
                    throw new \Exception("Connection to the '$groupNameForLog' DB failed: $errorMessage");
                }
            }
        }

        return $my_db;
    }

    /**
     * Make sure there is at least one valid sorting column if option includes sorting
     * @param string $option
     * @throws \Exception
     */
    private function assure_sorting(string $option) {
        if ($option == 'filtered_and_paged' || $option == 'filtered_and_sorted') {
            // Only need to dig in if there aren't any sorting items already
            if (empty($this->query_parts->sorting_items)) {
                // Use default sorting column or first column
                $col = $this->query_parts->sorting_default['col'];

                // Use default sorting direction or ASC
                $dir = $this->query_parts->sorting_default['dir'];
                if ($dir) {
                    // Assure that the sort direction is ASC or DESC
                    // Will auto-update desc or Descending to DESC; anything else is ASC
                    if (StartsWith(strtoupper($dir), "DESC")) {
                        $dir = "DESC";
                    } else {
                        $dir = "ASC";
                    }
                } else {
                    $dir = 'ASC';
                }

                if ($col) {
                    // Default column is defined
                    // It may contain multiple column names, separated by a comma. If so, append each separately
                    $colNames = explode(",", $col);
                    foreach ($colNames as $colName) {
                        $this->add_sorting_item(trim($colName), $dir);
                    }
                } else {
                    // Default column to sort on is not defined in the Model Config DB
                    // Sort on the first column
                    $col_info = $this->get_column_info();
                    if ($col_info) {
                        $col = $col_info[0]->name;
                    } else {
                        throw new \Exception('cannot find default sorting row for "filitered_and_paged" ');
                    }
                    $this->add_sorting_item($col, $dir);
                }
            }
        }
    }

    /**
     * Return the number of rows that would be generated by the query
     * without paging constraints (needed to make paging controls)
     *
     * Uses a cached value, if found, and if less than 1 hour old
     * (external code will call clear_cached_total_rows to force reload from DB)
     *
     * If no cached value is available, or if base SQL in cache does not match current base SQL,
     * reload total from database and cache it
     *
     * Calls procedure get_query_row_count_proc() to get the row counts for a given table or view and filter
     *
     * @return int
     * @throws \Exception
     */
    function get_total_rows() {
        $working_total = -1;

        // Call build_query_sql() to initialze $this->sql_builder->baseSQL
        $this->sql_builder->build_query_sql($this->query_parts, 'count_only');

        // Need to get current base sql to compare with cached version

        // Example base_sql:
        // " FROM V_Dataset_List_Report_2" or
        // " FROM V_Data_Package_Aggregation_List_Report WHERE Data_Package_ID = 194'
        $base_sql = $this->get_base_sql();

        // Get cached values, if any
        // $state object has properties base_sql, total, and cache_time
        $state = get_from_cache($this->total_rows_storage_name);

        if ($state) {
            $cache_age_hours = (time() - $state->cache_time) / 60.0 / 60.0;

            if ($state->base_sql == $base_sql && $cache_age_hours < 1) {
                $working_total = $state->total;
            }
        }

        if ($working_total < 0) {
            // Get total number of rows returned by the query in SELECT COUNT(*) FROM ...

            // Option 1: use "SELECT COUNT(*) FROM ..."
            // Option 2: call procedure get_query_row_count_proc()

            $my_db = $this->get_db_object($this->query_parts->dbn);

            // Call procedure get_query_row_count_proc()
            $row_count = 0;
            $message = "";

            $result = $this->get_total_rows_using_procedure($my_db, $base_sql, $row_count, $message);

            if ($result == 0) {
                $working_total = $row_count;
            } else {
                echo "($result):$message";

                // Set the row count to a negative number so it's obvious that the total row count could not be determined
                $working_total = -13;

                /*
                 * Uncomment this code block to use Option 1
                 *
                 * For this code block to be used, the above call to build_query_sql() needs to store the result in $sql
                 * The SQL returned by build_query_sql() is of the form
                 *   "SELECT COUNT(*) FROM v_analysis_job_list_report_2 WHERE [tool] LIKE '%MSGFPlus%'"
                 * $sql = $this->sql_builder->build_query_sql($this->query_parts, 'count_only');

                $result = 0;

                $query = $my_db->query($sql);
                if (!$query) {
                    $currentTimestamp = date("Y-m-d");
                    throw new \Exception("Error getting total row count from database; see writable/logs/log-$currentTimestamp.php");
                }

                if ($query->getNumRows() == 0) {
                    $currentTimestamp = date("Y-m-d");
                    throw new \Exception("Total count row was not returned; see writable/logs/log-$currentTimestamp.php");
                }

                $row = $query->getRow();
                $query->freeResult();
                $working_total = $row->numrows;

                 *
                 */
            }

            // Cache the row count for the given base_sql
            $state = new CachedTotalRows();
            $state->total = $working_total;
            $state->base_sql = $base_sql;
            $state->cache_time = time();

            save_to_cache($this->total_rows_storage_name, $state);
        }
        return $working_total;
    }

    /**
     * Call procedure get_query_row_count_proc() to determine the number of rows returned by the given base SQL
     * @param BaseConnection $my_db      DB object
     * @param string         $base_sql   Base SQL
     * @param int            $row_count  Row count
     * @param string         $sa_message Error message to return
     * @return int Return code: 0 if no errors, -1 if an error
     */
    function get_total_rows_using_procedure(BaseConnection $my_db, string $base_sql, &$row_count, &$sa_message) {
        // Use Sproc_sqlsrv with PHP 7 on Apache 2.4
        // Use Sproc_mssql  with PHP 5 on Apache 2.2
        // Set this based on the current DB driver

        $sprocHandler = "\\App\\Libraries\\Sproc_" . strtolower($my_db->DBDriver);
        $sproc_handler = new $sprocHandler();

        $sprocName = "get_query_row_count_proc";

        $input_params = new \stdClass();

        $args = array();

        $objectAndFilter = "";

        // $base_sql should start with " FROM", which we want to remove
        // The following test will work with or without the leading space
        $fromIndex = stripos($base_sql, "FROM");

        if ($fromIndex === 1) {
            $objectAndFilter = substr(trim($base_sql), 4);
        } elseif ($fromIndex === 0) {
            $objectAndFilter = substr($base_sql, 4);
        } else {
            $objectAndFilter = trim($base_sql);
        }

        // Extract out the WHERE clause from $objectAndFilter
        $queryParts = explode(" WHERE ", $objectAndFilter, 2);

        if (count($queryParts) > 1) {
            $objectName = $queryParts[0];
            $whereClause = $queryParts[1];
        } else {
            $objectName = $objectAndFilter;
            $whereClause = "";
        }

        $sproc_handler->AddLocalArgument($args, $input_params, "objectName", $objectName, "varchar", "input", 255);
        $sproc_handler->AddLocalArgument($args, $input_params, "whereClause", $whereClause, "varchar", "input", 4000);
        $sproc_handler->AddLocalArgument($args, $input_params, "rowCount", 0, "bigint", "output", 8);
        $sproc_handler->AddLocalArgument($args, $input_params, "message", "", "varchar", "output", 512);

        // Many functions check for and initialize the DB connection if not there,
        // but that leaves connection issues popping up in random places
        if (empty($my_db->connID)) {
            // $my_db->connID is normally an object
            // But if an error occurs or it disconnects, it is false/empty
            // Try initializing first
            $my_db->initialize();
        }

        $form_fields = array();

        $sproc_handler->execute($sprocName, $my_db->connID, $args, $input_params, $form_fields);

        // Examine the result code
        $result = $input_params->exec_result;
        $returnValue = $input_params->retval;

        if (!$result) {
            $row_count = 0;
            $sa_message = "Execution failed for $sprocName";
            return -1;
        }

        if ($returnValue != 0) {
            $row_count = 0;
            $sa_message = "Procedure error: " . $input_params->message . " ($returnValue for $sprocName)";
            return $returnValue;
        }

        $row_count = $input_params->rowCount;

        return 0;
    }

    // --------------------------------------------------------------------
    function get_cached_total_rows() {
        return get_from_cache($this->total_rows_storage_name);
    }

    // --------------------------------------------------------------------
    function clear_cached_total_rows() {
        helper('cache');
        clear_cache($this->total_rows_storage_name);
    }

    // --------------------------------------------------------------------
    function clear_cached_state() {
        helper('cache');
        clear_cache($this->total_rows_storage_name);
        clear_cache($this->col_info_storage_name);
    }

    /**
     * Get information about columns that would be generated by current query parts,
     * either from cache or by running a single-row query against database
     * @return array
     */
    function get_column_info(): array {

        $forceRefresh = false;
        if (!is_null($this->result_column_cachetime) &&
                (time() - $this->result_column_cachetime) / 60.0 > self::column_info_refresh_interval_minutes) {
            $forceRefresh = true;
        }

        if (!$this->result_column_info || $forceRefresh) {
            helper('cache');

            $state = get_from_cache($this->col_info_storage_name);

            if ($state) {
                $this->result_column_info = $state;
            } else {
                $state = $this->get_col_data();
                if ($state) {
                    $this->set_col_info_data($state);
                }
            }

            $this->result_column_cachetime = time();
        }
        return $this->result_column_info;
    }

    // --------------------------------------------------------------------
    function get_column_info_cache_name() {
        return $this->col_info_storage_name;
    }

    // --------------------------------------------------------------------
    function get_column_info_cache_data() {
        helper('cache');

        return get_from_cache($this->col_info_storage_name);
    }

    // --------------------------------------------------------------------
    private function set_col_info_data($state) {
        helper('cache');

        $this->result_column_info = $state;
        save_to_cache($this->col_info_storage_name, $state);
    }

    /**
     * Get a single row from database and remember the column information
     * @return array
     */
    private function get_col_data(): array {
        $sql = $this->sql_builder->build_query_sql($this->query_parts, 'column_data_only');

        $my_db = $this->get_db_object($this->query_parts->dbn);
        $query = $my_db->query($sql);
        $result_column_info = $query->getFieldData();
        // $query->freeResult();
        return $result_column_info;
    }

    /**
     * Get the column names
     * @return mixed Array of names
     */
    function get_col_names() {
        $cols = array();
        $col_info = $this->get_column_info();
        foreach ($col_info as $obj) {
            $cols[] = $obj->name;
        }
        return $cols;
    }

    /**
     * Get the data type for the given column
     * @param string $col Column Name
     * @return string
     */
    function get_column_data_type(string $col) {
        $type = '??';
        $col_info = $this->get_column_info();
        foreach ($col_info as $obj) {
            if ($col == $obj->name) {
                $type = $obj->type;
                break;
            }
        }
        return $type;
    }

    /**
     * Load the query specs from table utility_queries in the config DB
     * @param string $config_name
     * @throws \Exception
     */
    private function get_query_specs_from_config_db($config_name) {

        $db = new Connection(['database' => $this->configDBPath, 'dbdriver' => 'sqlite3']);

        $obj = $db->query("SELECT * FROM utility_queries WHERE name='$config_name'")->getRowObject();
        if (is_null($obj)) {
            throw new \Exception('Could not find query specs');
        }

        $db->close();

        $this->query_parts->dbn = $obj->db;
        $this->query_parts->table = $obj->table;
        $this->query_parts->columns = $obj->columns;
        $filters = (isset($obj->filters) && $obj->filters != '') ? json_decode($obj->filters, true) : array();
        $this->primary_filter_specs = array();
        foreach ($filters as $col => $cmp) {
            $name = "pf_" . str_replace(' ', '_', strtolower($col));
            $a = array();
            $a['label'] = $col;
            $a['col'] = $col;
            $a['cmp'] = $cmp;
            $a['value'] = '';
            $this->primary_filter_specs[$name] = $a;
        }

        $sorting = (isset($obj->sorting) && $obj->sorting != '') ? json_decode($obj->sorting, true) : array();
        if (!empty($sorting)) {
            $this->query_parts->sorting_default = $sorting;
        }
    }

    /**
     * Get the list report query specs from tables general_params, list_report_primary_filter, and primary_filter_choosers
     * @throws \Exception
     */
    private function get_list_report_query_specs_from_config_db() {
        $db = new Connection(['database' => $this->configDBPath, 'dbdriver' => 'sqlite3']);

        // Get list of tables in database
        $tbl_list = array();
        foreach ($db->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'")->getResultArray() as $row) {
            $tbl_list[] = $row['tbl_name'];
        }

        foreach ($db->query("SELECT * FROM general_params")->getResultArray() as $row) {
            switch ($row['name']) {
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

        if (in_array('list_report_primary_filter', $tbl_list)) {
            $this->primary_filter_specs = array();
            foreach ($db->query("SELECT * FROM list_report_primary_filter")->getResultArray() as $row) {
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
        if (in_array('primary_filter_choosers', $tbl_list)) {
            $fl = array();
            foreach ($db->query("SELECT * FROM primary_filter_choosers")->getResultArray() as $row) {
                $a = array();
                $a['type'] = $row['type'];
                $a['PickListName'] = $row['PickListName'];
                $a['Target'] = $row['Target'];
                $a['XRef'] = $row['XRef'];
                $a['Delimiter'] = $row['Delimiter'];
                $fl[$row['field']][] = $a;
            }
            foreach ($fl as $fn => $ch) {
                if (count($ch) == 1) {
                    $this->primary_filter_specs[$fn]['chooser_list'] = array($ch[0]);
                } else {
                    $this->primary_filter_specs[$fn]['chooser_list'] = $ch;
                }
            }
        }

        $db->close();
    }

    /**
     * Get the detail report query specs from the general_params table
     * @throws \Exception
     */
    private function get_detail_report_query_specs_from_config_db() {
        $db = new Connection(['database' => $this->configDBPath, 'dbdriver' => 'sqlite3']);

        $filterColumn = '';
        $filterComparison = '';

        $row = null;
        foreach ($db->query("SELECT * FROM general_params")->getResultArray() as $row) {
            switch ($row['name']) {
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
                    $filterColumn = $row['value'];
                    $filterComparison = 'MatchesText';
                    break;
                case 'detail_report_data_id_type':
                    switch ($row['value']) {
                        case 'integer':
                        case 'bigint':
                        case 'int':
                        case 'smallint':
                        case 'tinyint':
                        case 'bit':
                        case 'float':
                        case 'real':
                            $filterComparison = 'Equals';
                            break;
                        default:
                            $filterComparison = 'MatchesText';
                    }
                    break;
            }
        }

        $db->close();

        if (strlen($filterColumn) == 0) {
            throw new \Exception('Detail report ID column not defined (get_detail_report_query_specs_from_config_db)');
        }

        $a = array();
        $a['col'] = $filterColumn;
        $a['cmp'] = $filterComparison;  // 'MatchesText' or 'Equals'
        $a['label'] = $filterColumn;
        $this->primary_filter_specs[$row['name']] = $a;
    }

    /**
     * Get the entry page query specs from tables the general_params table
     * @throws \Exception
     */
    private function get_entry_page_query_specs_from_config_db() {
        $db = new Connection(['database' => $this->configDBPath, 'dbdriver' => 'sqlite3']);

        foreach ($db->query("SELECT * FROM general_params")->getResultArray() as $row) {
            switch ($row['name']) {
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

        $db->close();
    }

    // --------------------------------------------------------------------
    function get_config_name() {
        return $this->config_name;
    }

    // --------------------------------------------------------------------
    function get_config_source() {
        return $this->config_source;
    }

    // --------------------------------------------------------------------
    // Stuff for query filters
    // --------------------------------------------------------------------

    /**
     * Information from config DB about primary filter defined for config_name/config_source
     * @return array
     */
    function get_primary_filter_specs() {
        return $this->primary_filter_specs;
    }

    /**
     * Get the allowed comparisons for the given data type
     * @param string $type
     * @return mixed
     */
    function get_allowed_comparisons_for_type($type) {
        return $this->sql_builder->get_allowed_comparisons_for_type($type);
    }

    /**
     * Allowed query predicate operators: AND and OR
     * @return mixed
     */
    function get_allowed_rel_values() {
        return array("AND" => "AND", "OR" => "OR");
    }
}
?>
