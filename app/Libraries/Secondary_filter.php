<?php
namespace App\Libraries;

class Secondary_filter {

    const storage_name_root = "lr_sec_filter_";

    private $config_name = '';
    private $config_source = "";
    private $storage_name = "";
    private $qf_field_names = array('qf_rel_sel', 'qf_col_sel', 'qf_comp_sel', 'qf_comp_val');
    private $cur_qf_vals = array();
    private $qf_num_filters = 4;

    // --------------------------------------------------------------------
    function __construct() {
        // Include app/helpers/wildcard_conversion_helper.php
        helper('wildcard_conversion');
    }

    /**
     * Get the number of active filters
     * @return int
     */
    function get_num_filters(): int {
        return $this->qf_num_filters;
    }

    // --------------------------------------------------------------------
    // Get current secondary filter values either from POST
    // or from cache storage (session)
    function init($config_name, $config_source) {
        helper('cache');

        $this->config_name = $config_name;
        $this->config_source = $config_source;
        $this->storage_name = self::storage_name_root . $this->config_name . '_' . $this->config_source;

        $this->clear_query_filter();

        // Try to get current values of filters from POST
        $state = $this->get_current_filter_values_from_post();
        if ($state) {
            $this->cur_qf_vals = $state;
            save_to_cache($this->storage_name, $state);
        } else {
            // Try to get current values of filters from cache
            $state = get_from_cache($this->storage_name);
            if ($state) {
                $this->cur_qf_vals = $state;
            }
        }
    }

    /**
     * Get current values for secondary filter if present in POST
     * Otherwise return false
     * @return array|false
     */
    private function get_current_filter_values_from_post() {
        $request = \Config\Services::request();
        if ($request->getPost('qf_rel_sel')) {
            $filter_values = array();
            foreach ($this->qf_field_names as $name) {
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
     * Clear the filter
     */
    function clear_query_filter() {
        for ($i = 0; $i < $this->qf_num_filters; $i++) {
            foreach ($this->qf_field_names as $name) {
                $this->cur_qf_vals[$i][$name] = "";
            }
        }
    }

    /**
     * Collect info for building secondary filter display.
     * Combines information from given model with current state of this secondary filter
     * into a structure suitable for display or other output to be generated
     * @param \App\Models\Q_model $model
     * @param string $url
     * @return array
     */
    function collect_information_for_display(\App\Models\Q_model $model, string $url = "data/get_sql_comparison/"): array {
        // Get array of column names from model
        // and make paired array suitable for selector option list
        // and get name of first column in case there is no current value for column name
        $columnNames = $model->get_col_names();

        // Capitalize each column name and each letter after an underscore
        for ($i = 0; $i < count($columnNames); $i++)
        {
            $columnNames[$i] = ucfirst($columnNames[$i]);
            $columnNames[$i] = preg_replace_callback('/_[a-z]/',
                                    function ($matches) {
                                        return strtoupper($matches[0]);
                                    }, $columnNames[$i]);
        }

        $first_col = current($columnNames);
        $cols = array_combine($columnNames, $columnNames);

        $fx = array();
        $relSelOpts = $model->get_allowed_rel_values();
        for ($i = 0; $i < $this->qf_num_filters; $i++) {
            // Get current values for each field of current filter row
            $a = new \stdClass();
            $a->relSelOpts = $relSelOpts;
            if ($i < count($this->cur_qf_vals)) {
                $a->curRel = $this->cur_qf_vals[$i]['qf_rel_sel'];
                $a->curCol = $this->cur_qf_vals[$i]['qf_col_sel'];
                $a->curComp = $this->cur_qf_vals[$i]['qf_comp_sel'];
                $a->curVal = $this->cur_qf_vals[$i]['qf_comp_val'];
            } else {
                $a->curRel = "";
                $a->curCol = "";
                $a->curComp = "";
                $a->curVal = "";
            }

            // Make comparison selector options list for current
            // value of column (default to first column if current value is empty)
            $col = ($a->curCol) ? ($a->curCol) : $first_col;
            $data_type = $model->get_column_data_type(strtolower($col));
            $a->cmpSelOpts = $model->get_allowed_comparisons_for_type($data_type);

            // Set up selection parameters for column field including javascript
            // to refresh comparision selection list when column name selector is changed by user
            $c_url = site_url($url);
            $colSelID = "qf_col_sel_$i";
            $a->js = "id='" . $colSelID . "' onChange='dmsFilter.loadSqlComparisonSelector(\"qf_comp_sel_container_$i\", \"$c_url\", \"$colSelID\")'";
            $a->flds = $cols;
            $fx[$i] = $a;
        }
        return $fx;
    }

    /**
     * Get current filter values
     * @return array
     */
    function get_current_filter_values(): array {
        return $this->cur_qf_vals;
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
     * @return array
     */
    function get_cached_value(): array {
        return get_from_cache($this->storage_name);
    }

    /**
     * For building up current values from a simple ordered list
     * (usually URL seqments)
     * @param array $items
     * @return array
     */
    function get_filter_from_list(array $items): array {
        // Build filters from list items
        $filter_state = array();
        $numItems = count($items);
        $itemIdx = 0;
        $filterIdx = 0;
        while ($itemIdx < $numItems) {
            foreach ($this->qf_field_names as $name) {
                if ($itemIdx == 3) {
                    // Check for special wildcard text
                    $valueToStore = decode_special_values($items[$itemIdx]);
                } else {
                    // Replace any 'URL encoded' characters
                    $valueToStore = rawurldecode($items[$itemIdx]);
                }
                $filter_state[$filterIdx][$name] = $valueToStore;
                $itemIdx++;
            }
            $filterIdx++;
        }
        // Pad out filters
        $numFilters = count($filter_state);
        for ($j = $numFilters; $j < $this->qf_num_filters; $j++) {
            foreach ($this->qf_field_names as $name) {
                $filter_state[$j][$name] = "";
            }
        }
        return $filter_state;
    }

    /**
     * Save current filter values to the cache
     * @param array $filter_state
     */
    function save_filter_values(array $filter_state) {
        save_to_cache($this->storage_name, $filter_state);
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
