<?php
namespace App\Libraries;

class Primary_filter {

    const storage_name_root = "lr_pri_filter_";

    private $config_name = '';
    private $config_source = '';
    private $storage_name = '';
    private $cur_filter_values = null;

    // --------------------------------------------------------------------
    function __construct() {

    }

    /**
     * Get current secondary filter values either from POST
     * or from cache storage (session)
     * @param type $config_name
     * @param type $config_source
     * @param type $filter_specs
     */
    function init($config_name, $config_source, $filter_specs) {
        foreach (array_keys($filter_specs) as $id) {
            $filter_specs[$id]["value"] = '';
            $filter_specs[$id]['rel'] = ($filter_specs[$id]['cmp'] == 'Rp') ? 'ARG' : 'AND';
        }
        $this->cur_filter_values = $filter_specs;

        $CI =& get_instance();
        helper('cache');

        $this->config_name = $config_name;
        $this->config_source = $config_source;
        $this->storage_name = self::storage_name_root . $this->config_name . '_' . $this->config_source;

        $this->clear_query_filter();

        // try to get current values of filters from POST
        $state = $this->get_current_filter_values_from_post($filter_specs);
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
     * Get current values for secondary filter if present in POST.
     * Otherwise return false
     * @param type $filter_specs
     * @return boolean
     */
    private function get_current_filter_values_from_post($filter_specs) {
        // (someday) smarter extraction of primary filter values from POST:
        // There may be other items in the POST not relevant to primary filter.
        // Maybe we can check for the presence of any scalars that begin with "pf_"
        if (!empty($_POST)) {
            foreach (array_keys($filter_specs) as $id) {
                $filterVal = filter_input(INPUT_POST, $id, FILTER_SANITIZE_SPECIAL_CHARS);
                // Check for $filterVal being empty; cannot use empty() since '0' is considered empty
                if ($filterVal !== '') {
                    // Check for encoded tabs (introduced by filter_input) and change them back to true tab characters
                    // Also, trim whitespace
                    $filter_specs[$id]["value"] = trim(str_replace('&#9;', "	", $filterVal));
                }
            }
            return $filter_specs;
        } else {
            return false;
        }
    }

    /**
     * For building up current values from another source
     * (usually URL seqments)
     * @param type $field
     * @param type $value
     */
    function set_current_filter_value($field, $value) {
        $this->cur_filter_values[$field]['value'] = $value;
    }

    /**
     * Clear the value for each field in the filter
     */
    function clear_current_filter_values() {
        foreach ($this->cur_filter_values as $fld => &$spec) {
            $spec['value'] = '';
        }
    }

    /**
     * Save current filter values to cache
     * (typically used when set_current_filter_value has been used)
     */
    function save_current_filter_values() {
        save_to_cache($this->storage_name, $this->cur_filter_values);
    }

    /**
     * Reset (clear) the filter
     */
    private function clear_query_filter() {

    }

    /**
     * Get current filter values
     * @return type
     */
    function get_cur_filter_values() {
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
        helper('cache');
        clear_cache($this->storage_name);
    }
}
?>
