<?php
namespace App\Libraries;

// --------------------------------------------------------------------
// List report page section
// --------------------------------------------------------------------

class List_report {

    protected $config_source = '';
    protected $config_name = '';
    protected $tag = '';
    protected $title = '';

    // --------------------------------------------------------------------
    function __construct() {

    }

    // --------------------------------------------------------------------
    function init($config_name, $config_source, $controller) {
        $this->config_name = $config_name;
        $this->config_source = $config_source;

        $this->controller = $controller;
        $this->tag = $this->controller->my_tag;
        $this->title = $this->controller->my_title;
    }

    /**
     * Make list report page
     * @param type $mode
     */
    function list_report($mode) {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        // Include several helper methods

        // Description         | Helper File
        // --------------------|------------------------------------------
        // Error validation    | app/Helpers/form.php
        // Menu creation       | app/Helpers/menu.php
        // Link creation tools | app/Helpers/link_util.php
        // URI segment parsing | app/Helpers/url.php

        helper(['form', 'menu', 'link_util', 'url']);

        $this->controller->choosers = model('App\Models\Dms_chooser');

        $this->controller->load_mod('G_model', 'gen_model', 'na', $this->config_source);

        // Clear total rows cache in model to force getting value from database
        $this->controller->load_mod('Q_model', 'model', $this->config_name, $this->config_source);
        $this->controller->model->clear_cached_total_rows();

        // If there were extra segments for list report URL,
        // convert them to primary and secondary filter field values and cache those
        // and redirect back to ourselves without the trailing URL segments
        $segs = array_slice(getCurrentUriDecodedSegments(), 2);

        // Initially assume all items in $segs are primary filter items
        $pfSegs = $segs;

        // Initialize empty secondary filters
        $sfSegs = array();

        // Check for keyword "sfx" or "clear-sfx"
        $sfIdx = $this->get_secondary_filter_preset_idx($segs);

        if (!$sfIdx === false) {
            // Secondary filters are defined
            // Extract them out then update $pfSegs
            $sfSegs = array_slice($segs, $sfIdx + 1);
            $pfSegs = array_slice($segs, 0, $sfIdx);
            $this->set_sec_filter_from_url_segments($sfSegs);
        }

        if (!empty($segs)) {
            // Retrieve the primary filters
            $primary_filter_specs = $this->controller->model->get_primary_filter_specs();

            $this->set_pri_filter_from_url_segments($pfSegs, $primary_filter_specs);

            if ($sfIdx === false) {
                // Secondary filters were not defined in the URL
                // Clear any cached filter values
                $this->set_sec_filter_from_url_segments($sfSegs);
            }
            redirect()->to(site_url($this->tag . '/' . $mode));
        }

        $data['tag'] = $this->tag;
        $data['my_tag'] = $this->controller->my_tag;

        $data['title'] = $this->controller->gen_model->get_page_label($this->title, $mode);

        // Get stuff related to list report optional features
        $data['loading'] = ($mode === 'search') ? 'no_load' : '';
        $data['list_report_cmds'] = $this->controller->gen_model->get_param('list_report_cmds');
        $data['is_ms_helper'] = $this->controller->gen_model->get_param('is_ms_helper');
        $data['has_checkboxes'] = $this->controller->gen_model->get_param('has_checkboxes');
        $data['ops_url'] = site_url($this->controller->gen_model->get_param('list_report_cmds_url'));

        $data['check_access'] = [$this->controller, 'check_access'];
        $data['choosers'] = $this->controller->choosers;

        $data['nav_bar_menu_items'] = set_up_nav_bar('List_Reports', $this->controller);
        echo view('main/list_report', $data);
    }

    /**
     * Check segment array to see of there is a secondary filter preset
     * @param array $segs
     * @return int
     */
    private function get_secondary_filter_preset_idx($segs) {
        $result = false;
        $ns = count($segs);
        $nxt = "";
        $s = "";

        // Step through segments and look for secondary filter keywords
        for ($i = 0; $i < $ns; $i++) {
            // Clear secondary filter
            $s = $segs[$i];
            if ($s == "clear-sfx" && $i + 1 == $ns) {
                $result = $i;
                break;
            }
            // Verify keyword followed by relation
            if ($s == "sfx") {
                if ($i + 1 < $ns) {
                    $nxt = $segs[$i + 1];
                    if ($nxt == "AND" || $nxt == "OR") {
                        $result = $i;
                        break;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Initialize primary filter values from URL segments and cache them for subsequent queries
     * @param type $segs
     * @param type $primary_filter_specs
     */
    protected function set_pri_filter_from_url_segments($segs, $primary_filter_specs) {
        // Primary filter object (we will use it to cache field values)
        $this->controller->load_lib('Primary_filter', $this->config_name, $this->config_source, $primary_filter_specs);

        // Get list of just the names of primary filter fields
        $form_field_names = array_keys($primary_filter_specs);

        // Use entry page helper mojo to relate segments to filter fields
        helper(['entry_page']);
        $initial_field_values = get_values_from_segs($form_field_names, $segs);

        // We are completely replacing filter values, so get rid of any we pulled from cache
        $this->controller->primary_filter->clear_current_filter_values();

        // Update values in primary filter object
        foreach ($initial_field_values as $field => $value) {
            $this->controller->primary_filter->set_current_filter_value($field, $value);
        }

        // And cache the values we got from the segments
        $this->controller->primary_filter->save_current_filter_values();
    }

    /**
     * Initialize secondary filter values from URL segments and cache them for subsequent queries
     * @param type $segs
     */
    protected function set_sec_filter_from_url_segments($segs) {
        // Secondary filter object (we will use it to cache field values)
        $this->controller->load_lib('Secondary_filter', $this->config_name, $this->config_source);

        $filter_state = $this->controller->secondary_filter->get_filter_from_list($segs);
        $this->controller->secondary_filter->save_filter_values($filter_state);
    }

    /**
     * Make filter section for list report page
     * Returns HTML containing filter components arranged in the specified format
     * @param type $filter_display_mode
     * @category AJAX
     */
    function report_filter($filter_display_mode = 'advanced') {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        helper('form');
        helper(['filter', 'link_util']);

        $this->controller->load_mod('Q_model', 'data_model', $this->config_name, $this->config_source);
        $cols = $this->controller->data_model->get_col_names();

        $this->controller->load_lib('Paging_filter', $this->config_name, $this->config_source);
        $current_paging_filter_values = $this->controller->paging_filter->get_current_filter_values();

        $this->controller->load_mod('G_model', 'gen_model', 'na', $this->config_source);
        $persistSortColumns = $this->controller->gen_model->get_list_report_sort_persist_enabled();

        $options = array("PersistSortColumns" => $persistSortColumns);

        $this->controller->load_lib('Sorting_filter', $this->config_name, $this->config_source, $options);
        $current_sorting_filter_values = $this->controller->sorting_filter->get_current_filter_values();

        $this->controller->load_lib('Column_filter', $this->config_name, $this->config_source);
        $col_filter = $this->controller->column_filter->get_current_filter_values();

        $primary_filter_specs = $this->controller->data_model->get_primary_filter_specs();
        $this->controller->load_lib('Primary_filter', $this->config_name, $this->config_source, $primary_filter_specs);
        $current_primary_filter_values = $this->controller->primary_filter->get_cur_filter_values();

        $this->controller->load_lib('Secondary_filter', $this->config_name, $this->config_source);
        $sec_filter_display_info = $this->controller->secondary_filter->collect_information_for_display($this->controller->data_model, "$this->config_source/get_sql_comparison/");

        switch ($filter_display_mode) {
            case 'minimal':
                make_search_filter_minimal($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter);
                break;
            case 'maximal':
                make_search_filter_expanded($cols, $this->controller, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter);
                break;
            case 'intermediate':
                make_search_filter_expanded($cols, $this->controller, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter, $filter_display_mode);
                break;
        }
    }

    /**
     * Crete the HTML for a query filter comparison field selector for the given column name
     * @param string $column_name
     * @category AJAX
     */
    function get_sql_comparison($column_name) {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        $this->controller->load_mod('Q_model', 'model', $this->config_name, $this->config_source);
        $data_type = $this->controller->model->get_column_data_type(strtolower($column_name));
        $cmpSelOpts = $this->controller->model->get_allowed_comparisons_for_type($data_type);

        helper('form');
        echo form_dropdown('qf_comp_sel[]', $cmpSelOpts);
    }

    /**
     * Create HTML displaying the list report data rows for inclusion in list report page
     * @param string $option
     * @category AJAX
     */
    function report_data($option = 'rows') {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        $this->set_up_list_query();

        $this->controller->load_mod('R_model', 'link_model', 'na', $this->config_source);

        $this->controller->load_lib('Column_filter', $this->config_name, $this->config_source);
        $col_filter = $this->controller->column_filter->get_current_filter_values();

        $this->controller->cell_presentation = new \App\Libraries\Cell_presentation();
        $this->controller->cell_presentation->init($this->controller->link_model->get_list_report_hotlinks());
        $this->controller->cell_presentation->set_col_filter($col_filter);

        $rows = $this->controller->data_model->get_rows()->getResultArray();
        if (empty($rows)) {
            echo "<div id='data_message' >No rows found</div>";
        } else {
            $col_info = $this->controller->data_model->get_column_info();
            $this->controller->cell_presentation->fix_datetime_and_decimal_display($rows, $col_info);

            $qp = $this->controller->data_model->get_query_parts();
            $data['row_renderer'] = $this->controller->cell_presentation;
            $data['column_header'] = $this->controller->cell_presentation->make_column_header($rows, $qp->sorting_items);
            $data['rows'] = $rows;

            helper(['text']);
            echo view('main/list_report_data', $data);
        }
    }

    /**
     * Create HTML displaying the SQL behind the data or the URL for deep-linking to the page
     * @param string $what_info
     * @category AJAX
     */
    function report_info($what_info) {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        $this->set_up_list_query();

        switch ($what_info) {
            case "sql":
                echo $this->controller->data_model->get_sql("filtered_and_sorted");
                break;
            case "url":
                $filters = $this->set_up_list_query();
                echo $this->dump_filters($filters, $this->controller->my_tag);
                break;
        }
    }

    /**
     * Convert the filters into a string for use by report_info
     * @param type $filters
     * @param type $tag
     * @return string
     */
    private function dump_filters($filters, $tag) {
        $s = "";
        helper(['wildcard_conversion']);

        // Dump primary filter to segment list
        // Replace spaces with %20
        // Trim leading and trailing whitespace
        $pf = array();
        foreach ($filters["primary"] as $f) {
            $x = ($f["value"]) ? encode_special_values($f["value"]) : "-";
            $pf[] = str_replace(" ", "%20", trim($x));
        }
        $s .= rtrim(site_url(), "/") . "/$tag/report/" . implode("/", $pf);

        // Dump active secondary filters to array of segments
        $sf = array();

        $dateFilters = array("LaterThan", "EarlierThan");

        foreach ($filters["secondary"] as $f) {
            if ($f["qf_comp_val"]) {
                $y = "/" . encode_special_values($f["qf_rel_sel"]);
                $y .= "/" . encode_special_values($f["qf_col_sel"]);
                $y .= "/" . encode_special_values($f["qf_comp_sel"]);

                if (in_array($f["qf_comp_sel"], $dateFilters)) {
                    // Replace forward slashes with dashes
                    $y .= "/" . str_replace("/", "-", encode_special_values($f["qf_comp_val"]));
                } else {
                    $y .= "/" . encode_special_values($f["qf_comp_val"]);
                }

                $sf[] = str_replace(" ", "%20", trim($y));
            }
        }

        // Add secondary filter segments (if present)
        if (!empty($sf)) {
            $s .= "/sfx" . implode("", $sf);
        }

        return $s;
    }

    /**
     * Create HTML for the paging display and control element for inclusion in report pages
     * @category AJAX
     */
    function report_paging() {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        helper(['link_util']);
        $this->set_up_list_query();

        $current_filter_values = $this->controller->paging_filter->get_current_filter_values();

        // Pull together info necessary to do paging displays and controls
        // and use it to set up a pager object
        $this->controller->preferences = model('App\Models\Dms_preferences');
        $this->controller->list_report_pager = new \App\Libraries\List_report_pager();
        try {
            // Make HTML using pager
            $s = '';
            $total_rows = $this->controller->data_model->get_total_rows();
            $per_page = $current_filter_values['qf_rows_per_page'];
            $first_row = $current_filter_values['qf_first_row'];
            $this->controller->list_report_pager->set($first_row, $total_rows, $per_page);
            $pr = $this->controller->list_report_pager->create_links();
            $ps = $this->controller->list_report_pager->create_stats($this->controller);

            $s .= "<span class='LRepPager'>$ps</span>";
            $s .= "<span class='LRepPager'>$pr</span>";
            echo $s;
        } catch (\Exception $e) {
            echo "Paging controls could not be built.  " . $e->getMessage();
        }
    }

    /**
     * Set up query for database entity based on list report filtering
     * @return array Filter settings
     */
    protected function set_up_list_query() {
        // It all starts with a model
        $this->controller->load_mod('Q_model', 'data_model', $this->config_name, $this->config_source);

        // Primary filter
        $primary_filter_specs = $this->controller->data_model->get_primary_filter_specs();
        $this->controller->load_lib('Primary_filter', $this->config_name, $this->config_source, $primary_filter_specs);
        $current_primary_filter_values = $this->controller->primary_filter->get_cur_filter_values();

        // Secondary filter
        $this->controller->load_lib('Secondary_filter', $this->config_name, $this->config_source);
        $current_secondary_filter_values = $this->controller->secondary_filter->get_current_filter_values();

        // Paging filter
        $this->controller->load_lib('Paging_filter', $this->config_name, $this->config_source);
        $current_filter_values = $this->controller->paging_filter->get_current_filter_values();

        $this->controller->load_mod('G_model', 'gen_model', 'na', $this->config_source);
        $persistSortColumns = $this->controller->gen_model->get_list_report_sort_persist_enabled();

        $options = array("PersistSortColumns" => $persistSortColumns);

        // Sorting filter
        $this->controller->load_lib('Sorting_filter', $this->config_name, $this->config_source, $options);
        $current_sorting_filter_values = $this->controller->sorting_filter->get_current_filter_values();

        // Add filter values to data model to set up query
        foreach (array_values($current_primary_filter_values) as $pi) {
            $this->controller->data_model->add_predicate_item($pi['rel'], $pi['col'], $pi['cmp'], $pi['value']);
        }
        foreach ($current_secondary_filter_values as $pi) {
            $this->controller->data_model->add_predicate_item($pi['qf_rel_sel'], $pi['qf_col_sel'], $pi['qf_comp_sel'], $pi['qf_comp_val']);
        }
        foreach ($current_sorting_filter_values as $item) {
            $this->controller->data_model->add_sorting_item($item['qf_sort_col'], $item['qf_sort_dir']);
        }
        $this->controller->data_model->add_paging_item($current_filter_values['qf_first_row'], $current_filter_values['qf_rows_per_page']);

        $this->controller->data_model->convert_wildcards();

        // Return filter settings
        return array(
            "primary" => $current_primary_filter_values,
            "secondary" => $current_secondary_filter_values
        );
    }

    /**
     * Export a list report
     * @param string $format
     */
    function export($format) {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        helper(['export']);

        $this->set_up_list_query();

        $this->controller->load_mod('G_model', 'gen_model', 'na', $this->config_source);

        $this->controller->load_mod('R_model', 'link_model', 'na', $this->config_source);

        $rows = $this->controller->data_model->get_rows('filtered_and_sorted')->getResultArray();

        $this->controller->cell_presentation = new \App\Libraries\Cell_presentation();
        $this->controller->cell_presentation->init($this->controller->link_model->get_list_report_hotlinks());

        $col_info = $this->controller->data_model->get_column_info();
        $this->controller->cell_presentation->fix_datetime_and_decimal_display($rows, $col_info);

        $this->controller->load_lib('Column_filter', $this->config_name, $this->config_source);
        $col_filter = $this->controller->column_filter->get_current_filter_values();

        if(empty($col_filter)) {
            // Examine the list report's hotlinks to look for any columns tagged with no_export
            // Skip those columns when exporting data
            $col_filter = $this->controller->cell_presentation->get_columns_to_export($rows);
        }

        if ($format == 'excel') {
            $this->controller->cell_presentation->add_color_codes($rows);
            $col_alignment = $this->controller->cell_presentation->get_column_alignment($rows);
        }

        // (someday) list report document export - output helper needs to clean out newlines and so forth.

        if (empty($rows)) {
            echo '<p>The table appears to have no data.</p>';
        } else {
            switch ($format) {
                case 'excel':
                    export_to_excel($rows, $this->tag, $col_filter, $col_alignment);
                    break;
                case 'tsv':
                    export_to_tab_delimited_text($rows, $this->tag, $col_filter);
                    break;
                case 'json':
                    header("Content-type: application/json");
                    echo json_encode($rows);
                    break;
            }
        }
    }
}
?>
