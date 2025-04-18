<?php
namespace App\Libraries;

class Sorting_filter {

    const storage_name_root = "lr_sort_filter_";

    private $config_name = '';
    private $config_source = "";
    private $storage_name = "";

    private $field_names = array('qf_sort_col', 'qf_sort_dir');
    private $cur_filter_values = array();

    private $num_filters = 3;

    // --------------------------------------------------------------------
    function __construct() {

    }

    /**
     * Get current secondary filter values either from POST
     * or from cache storage (session)
     * @param string $config_name
     * @param string $config_source
     * @param \App\Controllers\BaseController $controller
     * @param array $options
     */
    function init(string $config_name, string $config_source, \App\Controllers\BaseController $controller, array $options) {
        helper('cache');

        $this->config_name = $config_name;
        $this->config_source = $config_source;
        $this->storage_name = self::storage_name_root . $this->config_name . '_' . $this->config_source;

        $this->clear_query_filter();

        $persistSortColumns = $options['PersistSortColumns'];

        // Try to get current values of filters from POST
        $state = $this->get_current_filter_values_from_post();
        if ($state) {
            $this->cur_filter_values = $state;
            if ($persistSortColumns !== false) {
                save_to_cache($this->storage_name, $state);
            }
        } else if ($persistSortColumns !== false) {
            // Try to get current values of filters from cache
            $state = get_from_cache($this->storage_name);
            if ($state) {
                $this->cur_filter_values = $state;
            }
        }
    }

    /**
     * Get current values for secondary filter if present in POST
     * otherwise return false
     * @return array|bool
     */
    private function get_current_filter_values_from_post() {
        $request = \Config\Services::request();

        if ($request->getPost('qf_sort_col')) {
            $filter_values = array();
            foreach ($this->field_names as $name) {
                $xar = $request->getPost($name);
                for ($i = 0; $i < count($xar); $i++) {
                    $filter_values[$i][$name] = trim($xar[$i]);
                }
            }
            return $filter_values;
        } else {
            return false;
        }
    }

    /**
     * Reset (clear) the filter
     */
    function clear_query_filter() {
        for ($i = 0; $i < $this->num_filters; $i++) {
            foreach ($this->field_names as $name) {
                $this->cur_filter_values[$i][$name] = "";
            }
        }
    }

    /**
     * Get current filter values
     * @return array
     */
    function get_current_filter_values(): array {
        return $this->cur_filter_values;
    }

    /**
     * Get the storage path
     * @return string
     */
    function get_storage_name(): string {
        return $this->storage_name;
    }

    /**
     * Get cached values
     * @return string
     */
    function get_cached_value(): string {
        return get_from_cache($this->storage_name);
    }

    /**
     * Clear cached data
     */
    function clear_cached_state() {
        helper('cache');
        clear_cache($this->storage_name);
    }
}
?>
