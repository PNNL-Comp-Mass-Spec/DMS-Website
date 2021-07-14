<?php
namespace App\Libraries;

class Column_filter {

    const storage_name_root = "lr_column_filter_";

    private $config_name = '';
    private $config_source = '';
    private $storage_name = '';

    /**
     * Array of column names to show
     * Empty array to show all columns
     * @var array
     */
    private $cur_filter_values = array();

    /**
     * Constructor
     */
    function __construct() {

    }

    /**
     * Get current secondary filter values either from POST
     * or from cache storage (session)
     * @param type $config_name
     * @param type $config_source
     */
    function init($config_name, $config_source) {
        $CI =& get_instance();
        helper('cache');

        $this->config_name = $config_name;
        $this->config_source = $config_source;
        $this->storage_name = self::storage_name_root . $this->config_name . '_' . $this->config_source;

        $this->clear_filter();

        // try to get current values of filters from POST
        $state = $this->get_current_filter_values_from_post();
        if ($state !== false) {
            $this->cur_filter_values = $state;
            save_to_cache($this->storage_name, $state);
        } else {
            // try to get current values of filters from cache
            $state = get_from_cache($this->storage_name);
            if ($state) {
                $this->cur_filter_values = $state;
            } else {
                // user preference defaults (if any)
            }
        }
    }

    /**
     * Get current values for filtering columns if present in POST
     * Otherwise return false
     * @return array
     */
    private function get_current_filter_values_from_post() {
        // We need to be able to tell the difference between an empty post
        // (signifying a new page visit) and a post that happens to contain
        // an empty list of columns.  The presence of "cf_column_selection_marker"
        // does that
        $selected_items = false;
        if (array_key_exists('cf_column_selection_marker', $_POST)) {
            if (array_key_exists('cf_column_selection', $_POST)) {
                $selected_items = filter_input(INPUT_POST, 'cf_column_selection', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
            } else {
                $selected_items = array();
            }
        }
        return $selected_items;
    }

    /**
     * Set filter so that it will not be used to filter results
     */
    private function clear_filter() {
        $this->cur_filter_values = array();
    }

    /**
     * Array of column names to show
     * Empty array to show all columns
     * @return array
     */
    function get_current_filter_values() {
        return $this->cur_filter_values;
    }

    // --------------------------------------------------------------------
    function get_storage_name() {
        return $this->storage_name;
    }

    // --------------------------------------------------------------------
    function get_cached_value() {
        return get_from_cache($this->storage_name);
    }

    // --------------------------------------------------------------------
    function clear_cached_state() {
        $CI =& get_instance();
        helper('cache');
        clear_cache($this->storage_name);
    }
}
?>
