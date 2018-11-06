<?php
// --------------------------------------------------------------------
// list report page section
// --------------------------------------------------------------------

class List_report {

    protected $config_source = '';
    protected $config_name = '';

    protected $tag = '';
    protected $title = '';

    // --------------------------------------------------------------------
    function __construct()
    {
    }

    // --------------------------------------------------------------------
    function init($config_name, $config_source)
    {
        $this->config_name = $config_name;
        $this->config_source = $config_source;

        $CI = &get_instance();
        $this->tag = $CI->my_tag;
        $this->title = $CI->my_title;
    }

    /**
     * Make list report page
     * @param type $mode
     */
    function list_report($mode)
    {
        $CI = &get_instance();
        session_start();
        $CI->load->helper(array('form', 'menu', 'link_util'));
        $CI->load->model('dms_chooser', 'choosers');

        $CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);

        // clear total rows cache in model to force getting value from database
        $CI->cu->load_mod('q_model', 'model', $this->config_name, $this->config_source);
        $CI->model->clear_cached_total_rows();

        // if there were extra segments for list report URL,
        // convert them to primary and secondary filter field values and cache those
        // and redirect back to ourselves without the trailing URL segments
        $segs = array_slice($CI->uri->segment_array(), 2);

        // Initially assume all items in $segs are primary filter items
        $pfSegs = $segs;

        // Initialize empty secondary filters
        $sfSegs = array();

        // Check for keyword "sfx" or "clear-sfx"
        $sfIdx = $this->get_secondary_filter_preset_idx($segs);

        if(!$sfIdx === false) {
            // Secondary filters are defined
            // Extract them out then update $pfSegs
            $sfSegs = array_slice($segs, $sfIdx + 1);
            $pfSegs = array_slice($segs, 0, $sfIdx);
            $this->set_sec_filter_from_url_segments($sfSegs);
        }

        if(!empty($segs)) {
            // Retrieve the primary filters
            $primary_filter_specs = $CI->model->get_primary_filter_specs();

            $this->set_pri_filter_from_url_segments($pfSegs, $primary_filter_specs);

            if ($sfIdx === false) {
                // Secondary filters were not defined in the URL
                // Clear any cached filter values
                $this->set_sec_filter_from_url_segments($sfSegs);
            }
            redirect($this->tag.'/'.$mode);
        }

        $data['tag'] = $this->tag;

        $data['title'] = $CI->gen_model->get_page_label($this->title, $mode);

        // get stuff related to list report optional features
        $data['loading'] = ($mode === 'search')?'no_load':'';
        $data['list_report_cmds'] = $CI->gen_model->get_param('list_report_cmds');
        $data['is_ms_helper'] = $CI->gen_model->get_param('is_ms_helper');
        $data['has_checkboxes'] = $CI->gen_model->get_param('has_checkboxes');
        $data['ops_url'] = site_url() . $CI->gen_model->get_param('list_report_cmds_url');

        $data['nav_bar_menu_items']= set_up_nav_bar('List_Reports');
        $CI->load->vars($data);
        $CI->load->view('main/list_report');
    }

    /**
     * Check segment array to see of there is a secondary filter preset
     * @param array $segs
     * @return int
     */
    private
    function get_secondary_filter_preset_idx($segs)
    {
        $result = false;
        $ns = count($segs);
        $nxt = "";
        $s = "";
        // step through segments and look for secondary filter keywords
        for($i = 0; $i < $ns; $i++) {
            // clear secondary filter
            $s = $segs[$i];
            if($s == "clear-sfx" && $i + 1 == $ns) {
                $result = $i;
                break;
            }
            // verify keyword followed by relation
            if($s == "sfx") {
                if($i + 1 < $ns) {
                    $nxt = $segs[$i + 1];
                    if($nxt == "AND" || $nxt == "OR") {
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
    protected
    function set_pri_filter_from_url_segments($segs, $primary_filter_specs)
    {
        $CI = &get_instance();

        // primary filter object (we will use it to cache field values)
        $CI->cu->load_lib('primary_filter', $this->config_name, $this->config_source, $primary_filter_specs);

        // get list of just the names of primary filter fields
        $form_field_names = array_keys($primary_filter_specs);

        // use entry page helper mojo to relate segments to filter fields
        $CI->load->helper(array('entry_page'));
        $initial_field_values = get_values_from_segs($form_field_names, $segs);

        // we are completely replacing filter values, so get rid of any we pulled from cache
        $CI->primary_filter->clear_current_filter_values();

        // update values in primary filter object
        foreach($initial_field_values as $field => $value) {
            $CI->primary_filter->set_current_filter_value($field, $value);
        }
        // and cache the values we got from the segments
        $CI->primary_filter->save_current_filter_values();
    }

    /**
     * Initialize secondary filter values from URL segments and cache them for subsequent queries
     * @param type $segs
     */
    protected
    function set_sec_filter_from_url_segments($segs)
    {
        $CI = &get_instance();

        // secondary filter object (we will use it to cache field values)
        $CI->cu->load_lib('secondary_filter', $this->config_name, $this->config_source);

        $filter_state = $CI->secondary_filter->get_filter_from_list($segs);
        $CI->secondary_filter->save_filter_values($filter_state);
    }

    /**
     * Make filter section for list report page
     * Returns HTML containing filter components arranged in the specified format
     * @param type $filter_display_mode
     * @category AJAX
     */
    function report_filter($filter_display_mode = 'advanced')
    {
        $CI = &get_instance();
        session_start();

        $CI->load->helper('form');
        $CI->load->helper(array('filter', 'link_util'));

        $CI->cu->load_mod('q_model', 'data_model', $this->config_name, $this->config_source);
        $cols = $CI->data_model->get_col_names();

        $CI->cu->load_lib('paging_filter', $this->config_name, $this->config_source);
        $current_paging_filter_values = $CI->paging_filter->get_current_filter_values();

        $CI->cu->load_lib('sorting_filter', $this->config_name, $this->config_source);
        $current_sorting_filter_values = $CI->sorting_filter->get_current_filter_values();

        $CI->cu->load_lib('column_filter', $this->config_name, $this->config_source);
        $col_filter = $CI->column_filter->get_current_filter_values();

        $primary_filter_specs = $CI->data_model->get_primary_filter_specs();
        $CI->cu->load_lib('primary_filter', $this->config_name, $this->config_source, $primary_filter_specs);
        $current_primary_filter_values = $CI->primary_filter->get_cur_filter_values();

        $CI->cu->load_lib('secondary_filter', $this->config_name, $this->config_source);
        $sec_filter_display_info = $CI->secondary_filter->collect_information_for_display($CI->data_model, "$this->config_source/get_sql_comparison/");

        switch($filter_display_mode) {
            case 'minimal':
                make_search_filter_minimal($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter);
                break;
            case 'maximal':
                make_search_filter_expanded($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter);
                break;
            case 'intermediate':
                make_search_filter_expanded($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter, $filter_display_mode);
                break;
        }
    }

    /**
     * Crete the HTML for a query filter comparison field selector for the given column name
     * @param string $column_name
     * @category AJAX
     */
    function get_sql_comparison($column_name)
    {
        $CI = &get_instance();
        session_start();

        $CI->cu->load_mod('q_model', 'model', $this->config_name, $this->config_source);
        $data_type = $CI->model->get_column_data_type($column_name);
        $cmpSelOpts = $CI->model->get_allowed_comparisons_for_type($data_type);

        $CI->load->helper('form');
        echo form_dropdown('qf_comp_sel[]', $cmpSelOpts);
    }

    /**
     * Create HTML displaying the list report data rows for inclusion in list report page
     * @param string $option
     * @category AJAX
     */
    function report_data($option = 'rows')
    {
        $CI = &get_instance();
        session_start();

        $this->set_up_list_query();

        $CI->cu->load_mod('r_model', 'link_model', 'na', $this->config_source);

        $CI->cu->load_lib('column_filter', $this->config_name, $this->config_source);
        $col_filter = $CI->column_filter->get_current_filter_values();

        $CI->load->library('cell_presentation');
        $CI->cell_presentation->init($CI->link_model->get_list_report_hotlinks());
        $CI->cell_presentation->set_col_filter($col_filter);

        $rows = $CI->data_model->get_rows()->result_array();
        if(empty($rows)) {
            echo "<div id='data_message' >No rows found</div>";
        } else {
            $col_info = $CI->data_model->get_column_info();
            $CI->cell_presentation->fix_datetime_and_decimal_display($rows, $col_info);

            $qp = $CI->data_model->get_query_parts();
            $data['row_renderer'] = $CI->cell_presentation;
            $data['column_header'] = $CI->cell_presentation->make_column_header($rows, $qp->sorting_items);
            $data['rows'] = $rows;

            $CI->load->helper(array('string'));
            $CI->load->vars($data);
            $CI->load->view('main/list_report_data');
        }
    }

    /**
     * Create HTML displaying the SQL behind the data or the URL for deep-linking to the page
     * @param string $what_info
     * @category AJAX
     */
    function report_info($what_info)
    {
        $CI = &get_instance();
        session_start();
        $this->set_up_list_query();

        switch($what_info) {
            case "sql":
                echo $CI->data_model->get_sql("filtered_and_sorted");
                break;
            case "url":
                $filters = $this->set_up_list_query();
                echo $this->dump_filters($filters, $CI->my_tag);
                break;
        }
    }

    /**
     * Convert the filters into a string for use by report_info
     * @param type $filters
     * @param type $tag
     * @return string
     */
    private
    function dump_filters($filters, $tag)
    {
        $s = "";

        // dump primary filter to segment list
        // Replace spaces with %20
        // Trim leading and trailing whitespace
        $pf = array();
        foreach($filters["primary"] as $f) {
            $x = ($f["value"]) ? $f["value"] : "-" ;
            $pf[] = str_replace(" ", "%20", trim($x));
        }
        $s .= site_url() . "$tag/report/" . implode("/", $pf);

        // dump active secondary filters to array of segments
        $sf = array();

        $dateFilters = array("LaterThan", "EarlierThan");

        foreach($filters["secondary"] as $f) {
            if($f["qf_comp_val"]) {
                $y = "/" . $f["qf_rel_sel"];
                $y .= "/" . $f["qf_col_sel"];
                $y .= "/" . $f["qf_comp_sel"];

                if (in_array( $f["qf_comp_sel"], $dateFilters)) {
                    // Replace forward slashes with dashes
                    $y .= "/" . str_replace("/", "-", $f["qf_comp_val"]);
                } else {
                    $y .= "/" . $f["qf_comp_val"];
                }

                $sf[] = str_replace(" ", "%20", trim($y));
            }
        }

        // add secondary filter segments (if present)
        if(!empty($sf)) {
            $s .= "/sfx" . implode("", $sf);
        }

        return $s;
    }


    /**
     * Create HTML for the paging display and control element for inclusion in report pages
     * @category AJAX
     */
    function report_paging()
    {
        $CI = &get_instance();
        session_start();

        $CI->load->helper(array('link_util'));
        $this->set_up_list_query();

        $current_filter_values = $CI->paging_filter->get_current_filter_values();

        // pull together info necessary to do paging displays and controls
        // and use it to set up a pager object
        $CI->load->model('dms_preferences', 'preferences');
        $CI->load->library(array('list_report_pager'));
        try {
            // make HTML using pager
            $s = '';
            $total_rows = $CI->data_model->get_total_rows();
            $per_page = $current_filter_values['qf_rows_per_page'];
            $first_row = $current_filter_values['qf_first_row'];
            $CI->list_report_pager->set($first_row, $total_rows, $per_page);
            $pr = $CI->list_report_pager->create_links();
            $ps = $CI->list_report_pager->create_stats();

            $s .= "<span class='LRepPager'>$ps</span>";
            $s .= "<span class='LRepPager'>$pr</span>";
            echo $s;
        } catch (Exception $e) {
            echo "Paging controls could not be built.  " . $e->getMessage();
        }

    }

    /**
     * Set up query for database entity based on list report filtering
     * @return array Filter settings
     */
    protected
    function set_up_list_query()
    {
        $CI = &get_instance();

        // it all starts with a model
        $CI->cu->load_mod('q_model', 'data_model', $this->config_name, $this->config_source);

        // primary filter
        $primary_filter_specs = $CI->data_model->get_primary_filter_specs();
        $CI->cu->load_lib('primary_filter', $this->config_name, $this->config_source, $primary_filter_specs);
        $current_primary_filter_values = $CI->primary_filter->get_cur_filter_values();

        // secondary filter
        $CI->cu->load_lib('secondary_filter', $this->config_name, $this->config_source);
        $current_secondary_filter_values = $CI->secondary_filter->get_current_filter_values();

        // paging filter
        $CI->cu->load_lib('paging_filter', $this->config_name, $this->config_source);
        $current_filter_values = $CI->paging_filter->get_current_filter_values();

        // sorting filter
        $CI->cu->load_lib('sorting_filter', $this->config_name, $this->config_source);
        $current_sorting_filter_values = $CI->sorting_filter->get_current_filter_values();

        // add filter values to data model to set up query
        foreach(array_values($current_primary_filter_values) as $pi) {
            $CI->data_model->add_predicate_item($pi['rel'], $pi['col'], $pi['cmp'], $pi['value']);
        }
        foreach($current_secondary_filter_values as $pi) {
            $CI->data_model->add_predicate_item($pi['qf_rel_sel'], $pi['qf_col_sel'], $pi['qf_comp_sel'], $pi['qf_comp_val']);
        }
        foreach($current_sorting_filter_values as $item) {
            $CI->data_model->add_sorting_item($item['qf_sort_col'], $item['qf_sort_dir']);
        }
        $CI->data_model->add_paging_item($current_filter_values['qf_first_row'], $current_filter_values['qf_rows_per_page']);

        $CI->data_model->convert_wildcards();

        // return filter settings
        return array(
            "primary" => $current_primary_filter_values,
            "secondary" => $current_secondary_filter_values
        );
    }


    /**
     * Export a list report
     * @param string $format
     */
    function export($format)
    {
        $CI = &get_instance();
        session_start();
        $CI->load->helper(array('export'));

        $this->set_up_list_query();

        $CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);

        $rows = $CI->data_model->get_rows('filtered_and_sorted')->result_array();

        $CI->load->library('cell_presentation');
        $col_info = $CI->data_model->get_column_info();
        $CI->cell_presentation->fix_datetime_and_decimal_display($rows, $col_info);

        $CI->cu->load_lib('column_filter', $this->config_name, $this->config_source);
        $col_filter = $CI->column_filter->get_current_filter_values();

        // (someday) list report document export - output helper needs to clean out newlines and so forth.

        if (empty($rows)) {
          echo '<p>The table appears to have no data.</p>';
        } else {
            switch($format) {
                case 'excel':
                    export_to_excel($rows, $this->tag, $col_filter);
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
