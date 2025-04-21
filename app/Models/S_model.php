<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\SQLite3\Connection;

// The function of this class is to execute a stored procedure
// against one of the databases defined in the app/Config/database file.

// It gets the procedure name and arguments from a config db as defined by the
// config_name and config_source. If the stored procedure returns a rowset,
// it is automatically saved and made accessible to external code.

/**
 * Helper class for stored procedure arguments:
 *   Basic definition of object that will contain bound arguments for calling stored procedure
 *   Only the baseline canonical arguments are statically defined by the class 
 *   - Stored-procedure specific arguments are added dynamically
 * @category Helper class
 */
class Bound_arguments extends \stdClass {

    var $retval = -1;
    var $mode = '';
    var $message = '';
    var $callingUser = '';
    var $exec_result = null;
}

/**
 * Used to execute a stored procedure against one of the databases defined in the app/Config/database file
 */
class S_model extends Model {

    // Some names used for caching
    const col_info_storage_name_root = "col_info_";

    private $col_info_storage_name = "";

    const total_rows_storage_name_root = "total_rows_";

    private $total_rows_storage_name = "";
    private $config_name = '';
    private $config_source = '';
    private $configDBPath = "";

    /**
     * Object that contains database-specific code used to actually access the stored procedure
     * @var \App\Libraries\Sproc_base
     */
    private \App\Libraries\Sproc_base $sproc_handler;

    /**
     * Actual name of stored procedure
     * May be different then config_name, which can reference aliases in general parameters table in config db
     * @var string
     */
    private $sprocName = '';

    /**
     * Definition of stored procedure arguments from config db
     * @var array
     */
    private $sproc_args = array();

    /**
     * Form fields from the config db
     * @var array
     */
    private $form_fields = array();

    /**
     * Database connection group from config db (general parameters table)
     * @var string
     */
    private $dbn = 'default';

    /**
     * Object whose fields are bound to actual arguments used for calling sproc
     * @var Bound_arguments
     */
    private $bound_calling_parameters = null;

    /**
     * Rowset returned by the stored procedure (null if none returned)
     * @var array
     */
    private $result_array = null;

    /**
     * Information about data columns in $result_array
     * @var array|null
     */
    private ?array $column_info = null;
    private $error_text = '';

    /**
     * Constructor
     */
    function __construct() {
        // Call the Model constructor
        parent::__construct();

        // Include the String operations methods
        helper('string');
    }

    // (someday) see if we can figure out how to get bound values updated when rowset is returned (mssql_next_result is not working )

    /**
     * Initialize objects
     * @param string $config_name
     * @param string $config_source
     * @return bool
     */
    function init(string $config_name, string $config_source = "ad_hoc_query") {
        $this->error_text = '';
        try {
            $this->config_name = $config_name;
            $this->config_source = $config_source;
            $this->col_info_storage_name = self::col_info_storage_name_root . $this->config_name . '_' . $this->config_source;
            $this->col_info_storage_name = self::col_info_storage_name_root . $this->config_name . '_' . $this->config_source;
            $this->total_rows_storage_name = self::total_rows_storage_name_root . $this->config_name . '_' . $this->config_source;

            $dbFileName = $config_source . '.db';

            helper(['config_db']);
            $this->configDBPath = get_model_config_db_path($dbFileName)->path;

            $this->_clear();

            $this->get_sproc_args_and_form_fields($config_name);

            return true;
        } catch (\Exception $e) {
            $this->error_text = $e->getMessage();
            return false;
        }
    }

    /**
     * Initializes stored procedure, binds arguments to paramObj members and
     * local variables, and calls the stored procedure, returning the result
     * @param \stdClass|null $parmObj
     * @return bool
     * @throws \Exception
     */
    function execute_sproc(?\stdClass $parmObj) {
        $this->error_text = '';
        helper(['string', 'database']);

        try {
            if (!isset($parmObj)) {
                throw new \Exception("Input parameter object was not supplied to execute_sproc for $this->sprocName");
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
                    $my_db = \Config\Database::connect(GetNullIfBlank($this->dbn));
                    update_search_path($my_db);

                    if ($my_db === false) {
                        // \Config\Database::connect() normally returns a database object
                        // But if an error occurs, it returns false?
                        // Retry establishing the connection
                        throw new \Exception('\Config\Database::connect returned false in S_model');
                    } else {
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
                    }
                } catch (\Exception $ex) {
                    $errorMessage = $ex->getMessage();
                    log_message('error', "Exception connecting to DB group '$this->dbn' (calling sproc $this->sprocName): $errorMessage");
                    $connectionRetriesRemaining--;
                    if ($connectionRetriesRemaining > 0) {
                        log_message('error', "Retrying connection to '$this->dbn' in $connectionSleepDelayMsec msec");
                        usleep($connectionSleepDelayMsec * 1000);
                        $connectionSleepDelayMsec *= 2;
                    } else {
                        throw new \Exception("Connection to DB group '$this->dbn' failed: $errorMessage");
                    }
                }
            }

            // Use Sproc_sqlsrv with PHP 7 on Apache 2.4
            // Use Sproc_mssql  with PHP 5 on Apache 2.2
            // Set this based on the current DB driver

            $this->set_my_sproc_handler("Sproc_" . strtolower($my_db->DBDriver));

            // Bind arguments to object
            // - create fields in local param object and bind sproc args to them
            // - set values of local object fields from corresponding fields in input object, if present
            //
            $this->bound_calling_parameters = new Bound_arguments();
            foreach ($this->sproc_args as $arg) {
                $fn = ($arg['field'] == '<local>') ? $arg['name'] : $arg['field'];

                if (isset($parmObj->$fn)) {
                    $this->bound_calling_parameters->$fn = $parmObj->$fn;
                } else {
                    $this->bound_calling_parameters->$fn = '';
                }
            }  // $this->bound_calling_parameters = $this->get_calling_args($parmObj); ??

            // Execute the stored procedure
            // Retry the call up to 4 times
            $execRetriesRemaining = 4;

            // The initial delay when retrying is 250 msec
            // This is doubled to 500 msec, then 1000, then 2000 msec if we end up retrying the connection
            $execSleepDelayMsec = 250;

            while ($execRetriesRemaining > 0) {
                try {
                    $this->sproc_handler->execute($this->sprocName, 
                                                  $my_db->connID, 
                                                  $this->sproc_args, 
                                                  $this->bound_calling_parameters,
                                                  $this->form_fields);
                    // Exit the while loop
                    break;
                } catch (\Exception $ex) {
                    $errorMessage = $ex->getMessage();
                    log_message('error', "Exception calling stored procedure $this->sprocName: $errorMessage");
                    $execRetriesRemaining--;
                    if ($execRetriesRemaining > 0) {
                        log_message('error', "Retrying call to $this->sprocName in $execSleepDelayMsec msec");
                        usleep($execSleepDelayMsec * 1000);
                        $execSleepDelayMsec *= 2;
                    } else {
                        throw new \Exception("Call to stored procedure $this->sprocName failed: $errorMessage");
                    }
                }
            }

            // What was the result?
            $result = $this->bound_calling_parameters->exec_result;

            if (!$result) {
                throw new \Exception("Execution failed for $this->sprocName");
            }

            // Figure out what kind of result we got, and handle it
            if ($result->hasRows) {
                // Rowset of data
                // Extract col metadata
                $this->column_info = $result->metadata;
                $this->cache_column_info();

                // Package results into array of arrays
                $this->result_array = $result->rows;
                $this->cache_total_rows();
            } else {
                // Procedure simply returns an error code; examine it
                $sproc_return_value = $this->bound_calling_parameters->retval;

                if (!is_numeric($sproc_return_value) && strlen($sproc_return_value) > 0 ||
                     is_numeric($sproc_return_value) && $sproc_return_value != 0) {

                    if (IsNullOrWhitespace($this->bound_calling_parameters->message)) {
                        $errorMessage = "Non-zero return code for $this->sprocName: $sproc_return_value";
                    } else {
                        $errorMessage = $this->bound_calling_parameters->message;

                        if (strpos($errorMessage, $sproc_return_value) === 0 ||
                            strpos($errorMessage, $this->sprocName) === 0) {
                            $errorMessage = $errorMessage . " (return code $sproc_return_value for $this->sprocName)";
                        }
                    }
                    throw new \Exception($errorMessage);
                }
            }

            return true;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            log_message('error', "Error in execute_sproc for $this->sprocName: $errorMessage");
            $this->error_text = $errorMessage;
            return false;
        }
    }

    // --------------------------------------------------------------------
    private function cache_total_rows() {
        helper('cache');

        save_to_cache($this->total_rows_storage_name, count($this->result_array));
    }

    // --------------------------------------------------------------------
    private function cache_column_info() {
        helper('cache');

        save_to_cache($this->col_info_storage_name, $this->column_info);
    }

    // --------------------------------------------------------------------
    function get_rows() {
        return $this->result_array;
    }

    // --------------------------------------------------------------------
    function get_filtered_rows(array $sorting_filter, array $paging_filter): array {
        $rows = $this->result_array;

        $table_sorter = new \App\Libraries\Table_sorter();
        /*
          foreach($sorting_filter as $sort) {
          $col = $sort['qf_sort_col'];
          $dir = $sort['qf_sort_dir'];
          if($col) {
          $rows = $table_sorter->sort($rows, $col, $dir);
          }
          }
         */

        if ($rows == null) {
            // No rows were returned by the database
            return array();
        }

        $sortedRows = $table_sorter->sort_multi_col($rows, $sorting_filter);
        if (!empty($paging_filter)) {
            $length = (int) $paging_filter['qf_rows_per_page'];
            $offset = (int) $paging_filter['qf_first_row'] - 1;
            $pagedRows = array_slice($sortedRows, $offset, $length);
            return $pagedRows;
        }
        return $sortedRows;
    }

    // --------------------------------------------------------------------
    function get_parameters(): Bound_arguments {
        return $this->bound_calling_parameters;
    }

    /**
     * Return the column information that was cached from the last execute_sproc that returned a rowset
     * @return array
     */
    function get_column_info(): array {
        helper('cache');

        $col_info = array();

        // Get cached values, if any
        $state = get_from_cache($this->col_info_storage_name);
        if ($state) {
            $col_info = $state;
        }
        return $col_info;
    }

    /**
     * Return the number of rows that was cached from the last execute_sproc that returned a rowset
     * @return int
     */
    function get_total_rows() {
        $working_total = -1;

        helper('cache');

        // Get cached values, if any
        $state = get_from_cache($this->total_rows_storage_name);
        if ($state) {
            $working_total = $state;
        }
        return $working_total;
    }

    // --------------------------------------------------------------------
    function get_col_names() {
        $cols = array();
        $col_info = $this->get_column_info();
        foreach ($col_info as $obj) {
            $cols[] = $obj->name;
        }
        return $cols;
    }

    // --------------------------------------------------------------------
    function get_sproc_args() {
        return $this->sproc_args;
    }

    /**
     * Return a list of fields for given sproc (minus the '<local>' fields)
     * @return array
     */
    function get_sproc_fields() {
        $fields = array();
        foreach ($this->sproc_args as $arg) {
            $field_name = $arg['field'];
            if ($field_name != '<local>') {
                $fields[] = $field_name;
            }
        }
        return $fields;
    }

    // --------------------------------------------------------------------
    function get_error_text() {
        return $this->error_text;
    }

    /**
     * Get a list of arguments for calling the stored procedure
     * based on configuration db definition and initialized from given param object
     * @param Bound_arguments $parmObj
     * @return Bound_arguments
     */
    function get_calling_args(Bound_arguments $parmObj): Bound_arguments {
        $callingParams = new Bound_arguments();
        foreach ($this->sproc_args as $arg) {
            $fn = ($arg['field'] == '<local>') ? $arg['name'] : $arg['field'];

            if (isset($parmObj->$fn)) {
                $callingParams->$fn = $parmObj->$fn;
            } else {
                $callingParams->$fn = '';
            }
        }
        return $callingParams;
    }

    /**
     * Load the stored procedure arguments from table "sproc_args" in the model config DB
     * Also load the form fields from table "form_fields"
     * @param string $config_name
     */
    private function get_sproc_args_and_form_fields(string $config_name) {
        $db = new Connection(['database' => $this->configDBPath, 'dbdriver' => 'sqlite3']);

        // Get list of tables in database
        $tbl_list = array();
        foreach ($db->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'")->getResultArray() as $row) {
            $tbl_list[] = $row['tbl_name'];
        }

        // Set name of stored procedure (subject to override by an alias from the general parameter table)
        $this->sprocName = $config_name;

        // Get parameters of interest from the general table
        foreach ($db->query("SELECT * FROM general_params")->getResultArray() as $row) {
            if ($row['name'] == 'my_db_group') {
                $this->dbn = $row['value'];
            } else
            if (strpos($row['name'], $config_name) !== false) { // (someday) require exact match for sproc name??
                // $config_name is alias for actual sproc name - change sproc name
                $this->sprocName = $row['value'];
            }
        }

        // Get definitions of arguments for stored procedure
        if (in_array('sproc_args', $tbl_list)) {
            $args = array();

            $sql = "select * from sproc_args where \"procedure\" = '$this->sprocName';";

            foreach ($db->query($sql)->getResultArray() as $row) {
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

        // Get definitions of arguments for stored procedure
        if (in_array('form_fields', $tbl_list)) {
            $fields = array();

            $sql = "select * from form_fields;";

            foreach ($db->query($sql)->getResultArray() as $row) {
                $fields[] = array(
                    'name'    => $row['name'],
                    'label'   => $row['label'],
                    'type'    => $row['type'],
                    'default' => $row['default'],
                    'rules'   => $row['rules']
                );
            }

            $this->form_fields = $fields;
        }

        $db->close();
    }

    // --------------------------------------------------------------------
    private function set_my_sproc_handler(string $hndlr_class) {
        $sprocHandler = "\\App\\Libraries\\$hndlr_class";
        $this->sproc_handler = new $sprocHandler();
    }

    // --------------------------------------------------------------------
    private function _clear() {

    }

    // --------------------------------------------------------------------
    function get_config_name() {
        return $this->config_name;
    }

    // --------------------------------------------------------------------
    function get_sproc_name() {
        return $this->sprocName;
    }

    // --------------------------------------------------------------------
    function get_config_source() {
        return $this->config_source;
    }

    // --------------------------------------------------------------------
    function clear_cached_state() {
        helper('cache');
        clear_cache($this->total_rows_storage_name);
        clear_cache($this->col_info_storage_name);
    }
}
?>
