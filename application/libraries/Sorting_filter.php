<?php

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
     * @param type $config_name
     * @param type $config_source
     */
    function init($config_name, $config_source) {
        $CI =& get_instance();
        $CI->load->helper('cache');

        $this->config_name = $config_name;
        $this->config_source = $config_source;
        $this->storage_name = self::storage_name_root . $this->config_name . '_' . $this->config_source;

        $this->clear_query_filter();

        // try to get current values of filters from POST
        $state = $this->get_current_filter_values_from_post();
        if ($state) {
            $this->cur_filter_values = $state;
            save_to_cache($this->storage_name, $state);
        } else {
            // try to get current values of filters from cache
            $state = get_from_cache($this->storage_name);
            if ($state) {
                $this->cur_filter_values = $state;
            }
        }
    }

    /**
     * Get current values for secondary filter if present in POST
     * otherwise return FALSE
     * @return boolean
     */
    private function get_current_filter_values_from_post() {
        $CI =& get_instance();

        if ($CI->input->post('qf_sort_col')) {
            $filter_values = array();
            foreach ($this->field_names as $name) {
                $xar = $CI->input->post($name);
                for ($i = 0; $i < count($xar); $i++) {
                    $filter_values[$i][$name] = trim($xar[$i]);
                }
            }
            return $filter_values;
        } else {
            return FALSE;
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
     * @return type
     */
    function get_current_filter_values() {
        return $this->cur_filter_values;
    }

    /**
     * Get the storage path
     * @return type
     */
    function get_storage_name() {
        return $this->storage_name;
    }

    /**
     * Get cached values
     * @return type
     */
    function get_cached_value() {
        return get_from_cache($this->storage_name);
    }

    /**
     * Clear cached data
     */
    function clear_cached_state() {
        $CI =& get_instance();
        $CI->load->helper('cache');
        clear_cache($this->storage_name);
    }

}
