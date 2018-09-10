<?php
// --------------------------------------------------------------------
// param report (stored procedure based list report) section
// --------------------------------------------------------------------

class Param_report {

    private $config_source = '';
    private $config_name = '';

    private $tag = '';
    private $title = '';
    
    // --------------------------------------------------------------------
    function __construct()
    {
    }

    // --------------------------------------------------------------------
    // list report page section
    // --------------------------------------------------------------------
    
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
     * Sets up a page that contains an entry form defined by the
     * e_model for the config db which will be used to get data
     * rows in HTML via and AJAX call to the param_data function.
     */
    function param()
    {
        $CI = &get_instance();

        // general specifications for page family
        $CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);
        
        // entry form
        $CI->cu->load_mod('e_model', 'form_model', 'na', $this->config_source);
        $form_def = $CI->form_model->get_form_def(array('fields', 'specs'));
        
        $CI->cu->load_lib('entry_form', $form_def->specs, $this->config_source);
        
        // get initial field values (from url segments) and merge them with form object
        $segs = array_slice($CI->uri->segment_array(), 2);
        $CI->load->helper(array('entry_page'));
        $initial_field_values = get_values_from_segs($form_def->fields, $segs);
        foreach($initial_field_values as $field => $value) {
            $CI->entry_form->set_field_value($field, $value);
        }
        $hdr = (empty($form_def->specs))?'':"<span class='filter_label'>Search Parameters</span>\n";
        $data['form'] = $hdr . $CI->entry_form->build_display("add");

        $data['title'] = $CI->gen_model->get_page_label($this->title, 'param');
        $data['tag'] = $this->tag;

        // get stuff related to list report optional features
//      $data['loading'] = ($mode === 'search')?'no_load':'';
        $data['list_report_cmds'] = $CI->gen_model->get_param('list_report_cmds');
        $data['is_ms_helper'] = $CI->gen_model->get_param('is_ms_helper');
        $data['has_checkboxes'] = $CI->gen_model->get_param('has_checkboxes');
        $data['ops_url'] = site_url() . $CI->gen_model->get_param('list_report_cmds_url');      

        $CI->load->helper(array('menu', 'link_util'));
        $data['nav_bar_menu_items']= set_up_nav_bar('Param_Pages');
        $CI->load->vars($data); 
        $CI->load->view('main/param_report');
    }

    /**
     * Returns HTML data row table of data returned by stored procedure.
     * This uses the stored procedure defined by the 'list_report_sproc'
     * parameter in the general_params table of the config db and expects
     * POST data from a form defined by the form_fields table of the config db
     * (via the e_model).
     * @return type
     * @category AJAX
     */
    function param_data()
    {
        $CI = &get_instance();
        session_start();
        $CI->load->helper('user');      

        $message = $this->get_data_rows_from_sproc();
        if($message) {
            echo "<div id='data_message' >$message</div>";
            return;
        }
        $rows = $this->get_filtered_param_report_rows();
        if(empty($rows)) {
            echo "<div id='data_message' >No rows found</div>";
        } else {
            $CI->cu->load_mod('r_model', 'link_model', 'na', $this->config_source);

            $CI->cu->load_lib('column_filter', $this->config_name, $this->config_source);
            $col_filter = $CI->column_filter->get_current_filter_values();
        
            $CI->load->library('cell_presentation');
            $CI->cell_presentation->init($CI->link_model->get_list_report_hotlinks());
            
            // (someday) roll the date fix into a function shareable with export_param
            $col_info = $CI->sproc_model->get_column_info();
            $CI->cell_presentation->fix_datetime_and_decimal_display($rows, $col_info);
            $CI->cell_presentation->set_col_filter($col_filter);
            
            $current_sorting_filter_values = $CI->sorting_filter->get_current_filter_values();
            $data['rows'] = $rows;  
            $data['row_renderer'] = $CI->cell_presentation;
            $data['column_header'] = $CI->cell_presentation->make_column_header($rows, $current_sorting_filter_values);

            $CI->load->helper(array('string'));
            $CI->load->vars($data); 
            $CI->load->view('main/param_report_data');
        }
    }

    /**
     * Get filtered data
     * @param type $paging
     * @return type
     */
    private
    function get_filtered_param_report_rows($paging = TRUE)
    {
        $CI = &get_instance();
        $CI->cu->load_lib('paging_filter', $this->config_name, $this->config_source);
        if($paging) {
            $current_paging_filter_values = $CI->paging_filter->get_current_filter_values();
        } else {
            $current_paging_filter_values = array();
        }
        
        $CI->cu->load_lib('sorting_filter', $this->config_name, $this->config_source);
        $current_sorting_filter_values = $CI->sorting_filter->get_current_filter_values();

        return $CI->sproc_model->get_filtered_rows($current_sorting_filter_values, $current_paging_filter_values);
    }

    /**
     * Get rowset from sproc specified by config_name/config_source
     * using parameters delivered from the entry form specified
     * by config_name/config_source, and set up controller for
     * call to $CI->sproc_model->get_rows();
     * @return type
     * @throws exception
     */
    private
    function get_data_rows_from_sproc()
    {
        $CI = &get_instance();
        // get specifications for the entry form
        // used for submission into POST and to be returned as HTML
        $CI->cu->load_mod('e_model', 'form_model', 'na', $this->config_source);
        $form_def = $CI->form_model->get_form_def(array('fields', 'rules'));
    
        $calling_params = new stdClass();
        if(empty($form_def->fields)) {
            $valid_fields = TRUE;
        } else {
            // make validation object and use it to 
            // get field values from POST and validate them
            $CI->load->helper('form');
            $CI->load->library('form_validation');
            $CI->form_validation->set_error_delimiters('<span class="bad_clr">', '</span>');
            $CI->form_validation->set_rules($form_def->rules);
            $valid_fields = $CI->form_validation->run();
            
            // get field values from validation object into an object
            // that will be used for calling stored procedure
            // and also putting values back into entry form HTML 
            foreach($form_def->fields as $field) {
                $calling_params->$field = $CI->form_validation->set_value($field);
            }
        }
        // parameters needed by stored procedure that are not in entry form specs
        $calling_params->mode = $CI->input->post('entry_cmd_mode');
        $calling_params->callingUser = get_user();

        $message = '';
        try {
            if (!$valid_fields) {
                throw new exception('There were validation errors: ' . validation_errors());
            }
            
            // call stored procedure        
            $ok = $CI->cu->load_mod('s_model', 'sproc_model',$this->config_name, $this->config_source);
            if(!$ok) {
                throw new exception($CI->sproc_model->get_error_text());
            }
            
            $success = $CI->sproc_model->execute_sproc($calling_params);
            if(!$success) {
                throw new exception($CI->sproc_model->get_error_text());
            }
            
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return $message;    
    }

    /**
     * Returns HTML for the paging display and control element 
     * for inclusion in param report pages
     * @category AJAX
     */
    function param_paging()
    {
        $CI = &get_instance();
        session_start();
        
        $CI->load->helper(array('link_util'));

        // current paging settings
        $CI->cu->load_lib('paging_filter', $this->config_name, $this->config_source);
        $current_paging_filter_values = $CI->paging_filter->get_current_filter_values();
        
        // model to get current row info
        $CI->cu->load_mod('s_model', 'sproc_model', $this->config_name, $this->config_source);
        
        // pull together info necessary to do paging displays and controls
        // and use it to set up a pager object
        $total_rows = $CI->sproc_model->get_total_rows();
        $per_page = $current_paging_filter_values['qf_rows_per_page'];
        $first_row = $current_paging_filter_values['qf_first_row'];

        // make HTML using pager
        $CI->load->model('dms_preferences', 'preferences');
        $CI->load->library(array('list_report_pager'));
        $s = '';
        $CI->list_report_pager->set($first_row, $total_rows, $per_page);
        $pr = $CI->list_report_pager->create_links();
        $ps = $CI->list_report_pager->create_stats();
        $s .= "<span class='LRepPager'>$ps</span>";
        $s .= "<span class='LRepPager'>$pr</span>";
        echo $s;
    }
    
    // --------------------------------------------------------------------
    // AJAX
    function param_filter()
    {
        $CI = &get_instance();
        session_start();
        
        // call stored procedure        
        $CI->cu->load_mod('s_model', 'sproc_model', $this->config_name, $this->config_source);
        $cols = $CI->sproc_model->get_col_names();

        $CI->cu->load_lib('paging_filter', $this->config_name, $this->config_source);
        $current_paging_filter_values = $CI->paging_filter->get_current_filter_values();

        $CI->cu->load_lib('sorting_filter', $this->config_name, $this->config_source);
        $current_sorting_filter_values = $CI->sorting_filter->get_current_filter_values();
        
        $CI->cu->load_lib('column_filter', $this->config_name, $this->config_source);
        $col_filter = $CI->column_filter->get_current_filter_values();

        $CI->load->helper('form');
        $CI->load->helper(array('filter', 'link_util'));
        make_param_filter($cols, $current_paging_filter_values, $current_sorting_filter_values, $col_filter);
    }

    /**
     * Export a param report
     * @param type $format
     * @return type
     */
    function export_param($format)
    {
        $CI = &get_instance();
        session_start();
        $CI->load->helper(array('user', 'export'));

        $message = $this->get_data_rows_from_sproc();
        if($message) {
            echo $message;
            return;
        }

        $rows = $this->get_filtered_param_report_rows(FALSE);

        // (someday) roll the date fix into a function shareable with param_data
        $CI->load->library('cell_presentation');
        $col_info = $CI->sproc_model->get_column_info();
        $CI->cell_presentation->fix_datetime_and_decimal_display($rows, $col_info);

        $CI->cu->load_lib('column_filter', $this->config_name, $this->config_source);
        $col_filter = $CI->column_filter->get_current_filter_values();
        
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
