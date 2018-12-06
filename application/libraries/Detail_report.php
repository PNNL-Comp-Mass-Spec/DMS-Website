<?php
// --------------------------------------------------------------------
// detail report page section
// --------------------------------------------------------------------

class Detail_report {

    private $config_source = '';
    private $config_name = '';

    private $tag = '';
    private $title = '';

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
     * Make a page to show a detailed report for the single record identified by the the user-supplied id
     * @param string $id
     */
    function detail_report($id)
    {
        $CI = &get_instance();

        $CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);
        $data['title'] = $CI->gen_model->get_page_label($this->title, 'show');

        $data['tag'] = $this->tag;
        $data['id'] = $id;
        $data['commands'] = $CI->gen_model->get_detail_report_commands();
        $dcmdp = $CI->gen_model->get_detail_report_cmds();
        $dcmds = array();
        foreach(explode(",", $dcmdp) as $dcmd) {
            $c = trim($dcmd);
            if($c) {
                $dcmds[] = $c;
            }
        }
        $data['detail_report_cmds'] = $dcmds;
        $data['aux_info_target'] = $CI->gen_model-> get_detail_report_aux_info_target();

        $CI->load->helper(array('detail_report', 'menu', 'link_util'));
        $data['nav_bar_menu_items']= set_up_nav_bar('Detail_Reports');
        $CI->load->vars($data);
        $CI->load->view('main/detail_report');
    }

    /**
     * Get detail report data for specified entity
     * @param string $id
     * @param boolean $show_entry_links
     * @param boolean $show_create_links
     * @throws exception
     * @category AJAX
     */
    function detail_report_data($id, $show_entry_links = TRUE, $show_create_links = TRUE)
    {
        $CI = &get_instance();

        try {
            // get data
            $CI->cu->load_mod('q_model', 'detail_model', $this->config_name, $this->config_source);
            $result_row = $CI->detail_model->get_item($id);
            if(empty($result_row)) {
                throw new exception("Details for entity '$id' could not be found");
            }

            $col_info = $CI->detail_model->get_column_info();

            // hotlinks
            $CI->cu->load_mod('r_model', 'link_model', 'na', $this->config_source);

            // Fix decimal-as-string display; datetimes are formatted in helpers/detail_report_helper.php
            $CI->load->library('cell_presentation');
            $rows = array(&$result_row);
            $CI->cell_presentation->fix_decimal_display($rows, $col_info);

            if (!($CI->cu->check_access('enter', FALSE))) {
                $show_entry_links = FALSE;
            }

            if (!($CI->cu->check_access('create', FALSE))) {
                $show_create_links = FALSE;
            }

            // render with old detail report helper
            $data['my_tag'] = $this->tag;
            $data['id'] = $id;
            $data["columns"] = $col_info;       // Column defs
            $data["fields"] = $result_row;      // Returned data for the retrieved row
            $data["hotlinks"] = $CI->link_model->get_detail_report_hotlinks();
            $data['show_entry_links'] = $show_entry_links;
            $data['show_create_links'] = $show_create_links;

            $CI->load->helper(array('string', 'detail_report_helper'));
            $CI->load->vars($data);
            $CI->load->view('main/detail_report_data');
        } catch (Exception $e) {
            echo "<div class='EPag_message' >" . $e->getMessage() . "</div>";
        }
    }

    /**
     * Returns HTML displaying the list report data rows for inclusion in list report page
     * @param string $id
     * @category AJAX
     */
    function detail_sql($id)
    {
        $CI = &get_instance();
        session_start();

        $CI->cu->load_mod('q_model', 'detail_model', $this->config_name, $this->config_source);
        echo $CI->detail_model->get_item_sql($id);
    }

    /**
     * Get aux info controls associated with specified entity
     * @param string $id
     * @category AJAX
     */
    function detail_report_aux_info_controls($id)
    {
        $CI = &get_instance();

        $CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);
        $aux_info_target = $CI->gen_model-> get_detail_report_aux_info_target();

        // aux_info always needs numeric ID, and sometimes ID for detail report is string
        // this is a bit of a hack to always get the number
        $CI->cu->load_mod('q_model', 'detail_model', $this->config_name, $this->config_source);
        $result_row = $CI->detail_model->get_item($id);
        if (!empty($result_row)) {
            $aux_info_id = (array_key_exists('ID', $result_row))?$result_row['ID']:$id;

            $CI->load->helper(array('string', 'detail_report_helper'));
            echo make_detail_report_aux_info_controls($aux_info_target, $aux_info_id, $id);
        }
    }

    /**
     * Export detailed report for the single record identified by the user-supplied id
     * @param string $id
     * @param string $format
     */
    function export_detail($id, $format)
    {
        $CI = &get_instance();
        session_start();

        // get entity data
        $CI->cu->load_mod('q_model', 'detail_model', $this->config_name, $this->config_source);
        $entity_info = $CI->detail_model->get_item($id);

        $aux_info_id = (array_key_exists('ID', $entity_info))?$entity_info['ID']:FALSE;
        $aux_info = array();
        if($aux_info_id) {
            $aux_info = $this->get_aux_info($aux_info_id);
        }

        $CI->load->helper(array('string', 'detail_report_helper', 'export'));
        switch($format) {
            case 'excel':
                export_detail_to_excel($entity_info, $aux_info, $this->tag."_detail");
                break;
            case 'tsv':
                export_detail_to_tab_delimited_text($entity_info, $aux_info, $this->tag."_detail");
                break;
            case 'json':
                header("Content-type: application/json");
                echo json_encode($entity_info);
                break;
            case 'test':
                print_r($entity_info); echo '<hr>';
                echo "$aux_info_id <hr>";
                print_r($aux_info); echo '<hr>';
                break;
        }
    }

    // --------------------------------------------------------------------
    private
    function get_aux_info($aux_info_id)
    {
        $CI = &get_instance();
        $CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);
        $aux_info_target = $CI->gen_model-> get_detail_report_aux_info_target();

        // get aux into data
        $CI->cu->load_mod('q_model', 'aux_info_model', '', '');
        $CI->aux_info_model->set_columns('Target, Target_ID, Category, Subcategory, Item, Value, SC, SS, SI');
        $CI->aux_info_model->set_table('V_AuxInfo_Value');
        $CI->aux_info_model->add_predicate_item('AND', 'Target', 'Equals', $aux_info_target);
        $CI->aux_info_model->add_predicate_item('AND', 'Target_ID', 'Equals', $aux_info_id);
        $CI->aux_info_model->add_sorting_item('SC');
        $CI->aux_info_model->add_sorting_item('SS');
        $CI->aux_info_model->add_sorting_item('SI');
        return $CI->aux_info_model->get_rows('filtered_and_sorted')->result_array();
    }

    // --------------------------------------------------------------------
    private
    function get_entry_aux_info($id)
    {
        $CI = &get_instance();

        $aux_info = array();
        $CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);
        $aux_info_target = $CI->gen_model-> get_detail_report_aux_info_target();
        if($aux_info_target) {
            // get data
            $CI->cu->load_mod('q_model', 'detail_model', 'detail_report', $this->config_source);
            $result_row = $CI->detail_model->get_item($id);
            // get aux info data
            $aux_info_id = (array_key_exists('ID', $result_row))?$result_row['ID']:$id;
            $aux_info = $this->get_aux_info($aux_info_id);
        }
        return $aux_info;
    }

    /**
     * Get the field information that would appear on the entry page for the given entity (label -> value)
     * @param string $id
     * @return type
     */
    private
    function get_entry_tracking_info($id)
    {
        $CI = &get_instance();

        // get definition of fields for entry page
        $CI->cu->load_mod('e_model', 'form_model', 'na', $this->config_source);
        $form_def = $CI->form_model->get_form_def(array('fields', 'specs', 'load_key'));

        $CI->cu->load_lib('entry_form', $form_def->specs, $this->config_source);

        // Get entry field values for this entity
        $CI->cu->load_mod('q_model', 'input_model', 'entry_page', $this->config_source);
        $field_values = $CI->input_model->get_item($id);

        // get entity key field
        $primary_key = $form_def->load_key;

        // The form field type may contain several keywords specified by a vertical bar       
        $fieldTypes = explode('|', $spec['type']);
        
        // Get array of field labels associated with field values
        // make sure key field is first in list
        $entity_info[$form_def->specs[$primary_key]['label']] = $field_values[$primary_key];
        foreach($form_def->specs as $field => $spec) {
            if($field != $primary_key && !in_array('hidden', $fieldTypes) && !in_array('non-edit', $fieldTypes)) {
                $entity_info[$spec['label']] = $field_values[$field];
            }
        }
        return $entity_info;
    }

    /**
     * Export spreadsheet template for the single record identified by the the user-supplied id
     * @param string $id
     * @param string $format
     */
    function export_spreadsheet($id, $format)
    {
        $CI = &get_instance();
        session_start();

        $entity_info = $this->get_entry_tracking_info($id);
        $aux_info = $this->get_entry_aux_info($id);

        $CI->load->helper(array('export'));
        switch($format) {
            case 'data':
                export_spreadsheet($this->tag, $entity_info, $aux_info, $this->tag."_template");
                break;
            case 'blank':
                export_spreadsheet($this->tag, $entity_info, $aux_info, $this->tag."_template");
                break;
            case 'test':
                dump_spreadsheet($entity_info, $aux_info);
                break;
        }
    }

    /**
     * Display contents of given script as graph
     * @param string $scriptName
     * @param type $config_source
     */
    function dot($scriptName, $config_source )
    {
        $CI = &get_instance();
        $CI->load->helper(array('url', 'string', 'export'));
        $config_name = 'dot';

        $CI->cu->load_mod('q_model', 'detail_model', $config_name, $config_source);
        $result_row = $CI->detail_model->get_item($scriptName);
        $script = $result_row['Contents'];
        $description = $result_row['Description'];

        export_xml_to_dot($scriptName, $description, $script);
    }

}
