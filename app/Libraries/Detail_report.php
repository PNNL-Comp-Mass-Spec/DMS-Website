<?php
namespace App\Libraries;

// --------------------------------------------------------------------
// Detail report page section
// --------------------------------------------------------------------

class Detail_report {

    private $config_source = '';
    private $config_name = '';
    private $tag = '';
    private $title = '';

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
     * Make a page to show a detailed report for the single record identified by the the user-supplied id
     * @param string $id
     */
    function detail_report($id) {
        $this->controller->load_mod('G_model', 'gen_model', 'na', $this->config_source);
        $data['title'] = $this->controller->gen_model->get_page_label($this->title, 'show');

        $data['tag'] = $this->tag;
        $data['my_tag'] = $this->controller->my_tag;
        $data['id'] = $id;
        $data['commands'] = $this->controller->gen_model->get_detail_report_commands();
        $dcmdp = $this->controller->gen_model->get_detail_report_cmds();
        $dcmds = array();
        foreach (explode(",", $dcmdp) as $dcmd) {
            $c = trim($dcmd);
            if ($c) {
                $dcmds[] = $c;
            }
        }
        $data['detail_report_cmds'] = $dcmds;
        $data['ops_url'] = site_url($this->controller->my_tag . '/operation');
        $data['aux_info_target'] = $this->controller->gen_model->get_detail_report_aux_info_target();
        $data['check_access'] = [$this->controller, 'check_access'];

        helper(['detail_report', 'menu', 'link_util']);
        $data['nav_bar_menu_items'] = set_up_nav_bar('Detail_Reports', $this->controller);
        echo view('main/detail_report', $data);
    }

    /**
     * Get detail report data for specified entity
     * @param string $id
     * @param boolean $show_entry_links
     * @param boolean $show_create_links
     * @throws exception
     * @category AJAX
     */
    function detail_report_data($id, $show_entry_links = true, $show_create_links = true) {
        try {
            // Get data
            $this->controller->load_mod('Q_model', 'detail_model', $this->config_name, $this->config_source);
            $result_row = $this->controller->detail_model->get_item($id, $this->controller);
            if (empty($result_row)) {
                throw new \Exception("Details for entity '$id' could not be found");
            }

            $col_info = $this->controller->detail_model->get_column_info();

            // Hotlinks
            $this->controller->load_mod('R_model', 'link_model', 'na', $this->config_source);

            // Fix decimal-as-string display; datetimes are formatted in helpers/detail_report_helper.php
            $this->controller->cell_presentation = new \App\Libraries\Cell_presentation();
            $rows = array(&$result_row);
            $this->controller->cell_presentation->fix_decimal_display($rows, $col_info);

            if (!($this->controller->check_access('enter', false))) {
                $show_entry_links = false;
            }

            if (!($this->controller->check_access('create', false))) {
                $show_create_links = false;
            }

            // Render with old detail report helper
            $data['my_tag'] = $this->tag;
            $data['id'] = $id;
            $data["columns"] = $col_info;       // Column defs
            $data["fields"] = $result_row;      // Returned data for the retrieved row
            $data["hotlinks"] = $this->controller->link_model->get_detail_report_hotlinks();
            $data['show_entry_links'] = $show_entry_links;
            $data['show_create_links'] = $show_create_links;
            //$data['label_formatter'] = new \App\Libraries\Label_formatter();

            helper(['text', 'detail_report_helper']);
            echo view('main/detail_report_data', $data);
        } catch (\Exception $e) {
            echo "<div class='EPag_message' >" . $e->getMessage() . "</div>";
        }
    }

    /**
     * Returns HTML displaying the list report data rows for inclusion in list report page
     * @param string $id
     * @category AJAX
     */
    function detail_sql($id) {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        $this->controller->load_mod('Q_model', 'detail_model', $this->config_name, $this->config_source);
        echo $this->controller->detail_model->get_item_sql($id);
    }

    /**
     * Get aux info controls associated with specified entity
     * @param string $id
     * @category AJAX
     */
    function detail_report_aux_info_controls($id) {
        $this->controller->load_mod('G_model', 'gen_model', 'na', $this->config_source);
        $aux_info_target = $this->controller->gen_model->get_detail_report_aux_info_target();

        // Aux_info always needs numeric ID, and sometimes ID for detail report is string
        // This is a bit of a hack to always get the number
        $this->controller->load_mod('Q_model', 'detail_model', $this->config_name, $this->config_source);
        $result_row = array_change_key_case($this->controller->detail_model->get_item($id, $this->controller), CASE_LOWER);
        if (!empty($result_row)) {
            $aux_info_id = (array_key_exists('id', $result_row)) ? $result_row['id'] : $id;

            helper(['text', 'detail_report_helper']);
            echo make_detail_report_aux_info_controls($aux_info_target, $aux_info_id, $id);
        }
    }

    /**
     * Export detailed report for the single record identified by the user-supplied id
     * @param string $id
     * @param string $format
     */
    function export_detail($id, $format) {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        // Get entity data
        $this->controller->load_mod('Q_model', 'detail_model', $this->config_name, $this->config_source);
        $entity_info = $this->controller->detail_model->get_item($id, $this->controller);

        $aux_info_id = (array_key_exists('ID', $entity_info)) ? $entity_info['ID'] : ((array_key_exists('id', $entity_info)) ? $entity_info['id'] : false);
        $aux_info = array();

        $this->controller->load_mod('G_model', 'gen_model', 'na', $this->config_source);
        $aux_info_target = $this->controller->gen_model->get_detail_report_aux_info_target();

        if ($aux_info_id && $aux_info_target) {
            $aux_info = $this->get_aux_info($aux_info_id);
        }

        helper(['text', 'detail_report_helper', 'export']);
        switch ($format) {
            case 'excel':
                export_detail_to_excel($entity_info, $aux_info, $this->tag . "_detail");
                break;
            case 'tsv':
                export_detail_to_tab_delimited_text($entity_info, $aux_info, $this->tag . "_detail");
                break;
            case 'json':
                \Config\Services::response()->setContentType("application/json");
                echo json_encode($entity_info);
                break;
            case 'test':
                print_r($entity_info);
                echo '<hr>';
                echo "$aux_info_id <hr>";
                print_r($aux_info);
                echo '<hr>';
                break;
        }
    }

    // --------------------------------------------------------------------
    private function get_aux_info($aux_info_id) {
        $this->controller->load_mod('G_model', 'gen_model', 'na', $this->config_source);
        $aux_info_target = $this->controller->gen_model->get_detail_report_aux_info_target();

        // Get aux into data
        $this->controller->load_mod('Q_model', 'aux_info_model', '', '');
        $this->controller->aux_info_model->set_columns('Target, Target_ID, Category, Subcategory, Item, Value, SC, SS, SI');
        $this->controller->aux_info_model->set_table('V_Aux_Info_Value');
        $this->controller->aux_info_model->add_predicate_item('AND', 'Target', 'Equals', $aux_info_target);
        $this->controller->aux_info_model->add_predicate_item('AND', 'Target_ID', 'Equals', $aux_info_id);
        $this->controller->aux_info_model->add_sorting_item('SC');
        $this->controller->aux_info_model->add_sorting_item('SS');
        $this->controller->aux_info_model->add_sorting_item('SI');
        return $this->controller->aux_info_model->get_rows('filtered_and_sorted')->getResultArray();
    }

    // --------------------------------------------------------------------
    private function get_entry_aux_info($id) {
        $aux_info = array();
        $this->controller->load_mod('G_model', 'gen_model', 'na', $this->config_source);
        $aux_info_target = $this->controller->gen_model->get_detail_report_aux_info_target();

        if ($aux_info_target) {
            // Get data
            $this->controller->load_mod('Q_model', 'detail_model', 'detail_report', $this->config_source);
            $result_row = $this->controller->detail_model->get_item($id, $this->controller);
            // Get aux info data
            $aux_info_id = (array_key_exists('ID', $result_row)) ? $result_row['ID'] : ((array_key_exists('id', $result_row)) ? $result_row['id'] : $id);
            $aux_info = $this->get_aux_info($aux_info_id);
        }

        return $aux_info;
    }

    /**
     * Get the field information that would appear on the entry page for the given entity (label -> value)
     * Skips hidden and non-edit fields
     * @param string $id
     * @return type
     */
    private function get_entry_tracking_info($id) {
        // Get definition of fields for entry page
        $this->controller->load_mod('E_model', 'form_model', 'na', $this->config_source);
        $form_def = $this->controller->form_model->get_form_def(array('fields', 'specs', 'load_key'));

        $this->controller->load_lib('Entry_form', $form_def->specs, $this->config_source);

        // Get entry field values for this entity
        $this->controller->load_mod('Q_model', 'input_model', 'entry_page', $this->config_source);
        $field_values = $this->controller->input_model->get_item($id, $this->controller);

        // Get entity key field
        $primary_key = $form_def->load_key;

        // The form field type may contain several keywords specified by a vertical bar
        $primaryKeyFieldTypes = explode('|', $form_def->specs[$primary_key]['type']);

        // Get array of field labels associated with field values
        // Make sure the primary key field is first in list
        // However, if the primary key field is a non-edit field, do not add it

        if (!in_array('hidden', $primaryKeyFieldTypes) && !in_array('non-edit', $primaryKeyFieldTypes)) {
            $entity_info[$form_def->specs[$primary_key]['label']] = $field_values[$primary_key];
        }

        foreach ($form_def->specs as $field => $spec) {
            $fieldTypes = explode('|', $spec['type']);

            if ($field != $primary_key && !in_array('hidden', $fieldTypes) && !in_array('non-edit', $fieldTypes)) {
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
    function export_spreadsheet($id, $format, $rowStyle = false, $ext = "tsv") {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        $entity_info = $this->get_entry_tracking_info($id);
        $aux_info = $this->get_entry_aux_info($id);

        helper(['export']);
        switch ($format) {
            case 'data':
                export_spreadsheet($this->tag, $entity_info, $aux_info, $rowStyle, $ext, $this->tag . "_template");
                break;
            case 'blank':
                export_spreadsheet($this->tag, $entity_info, $aux_info, $rowStyle, $ext, $this->tag . "_template");
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
    function dot($scriptName, $config_source) {
        helper(['url', 'text', 'export']);
        $config_name = 'dot';

        $this->controller->load_mod('Q_model', 'detail_model', $config_name, $config_source);
        $result_row = array_change_key_case($this->controller->detail_model->get_item($scriptName, $this->controller), CASE_LOWER);
        $script = $result_row['contents'];
        $description = $result_row['description'];

        export_xml_to_dot($scriptName, $description, $script);
    }
}
?>
