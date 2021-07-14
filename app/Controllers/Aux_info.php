<?php
namespace App\Controllers;

// Include the String operations methods
require_once(BASEPATH . '../application/libraries/String_operations.php');

class Aux_info extends BaseController {

    /**
     * Constructor
     */
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();
///--
        $this->helpers = array_merge($this->helpers, ['dms_search', 'cookie', 'user', 'dms_logging']);

        session_start();
        $this->preferences = model('App\Models\dms_preferences');
        $this->choosers = model('App\Models\dms_chooser');

        $this->help_page_link = $this->config->item('pwiki');
        $this->help_page_link .= $this->config->item('wikiHelpLinkPrefix');

        $this->color_code = $this->config->item('version_color_code');
///--
        $this->my_tag = "aux_info";
        $this->my_model = "M_aux_info";
        $this->my_title = "Aux Info";
        $this->my_list_action = "aux_info/report";
        $this->my_export_action = "aux_info/export";

        $this->load->helper(array('url', 'string', 'form'));
        $this->model = model('App\\Models\\'.$this->my_model);

        $this->load->library('aux_info_support');
    }

    /**
     * Set aux info names
     * @param type $target
     * @param type $id
     */
    private function _set_aux_info_names($target, $id='')
    {
        $this->aux_info_support->item_entry_url = site_url('aux_info/item_values/'.$target.'/');
        $this->aux_info_support->copy_info_url =  site_url('aux_info_copy/update/');
        $this->aux_info_support->update_info_url = site_url("aux_info/update");
        $this->aux_info_support->show_url = site_url("aux_info/show/".$target."/".$id);
    }

    /**
     * Returns HTML to display current values for aux info items for
     * given target and entity given by id
     * @param type $target
     * @param type $id
     */
    function show($target, $id)
    {
        $this->load->helper('menu');

        // nav_bar setup
        $this->menu = model('App\Models\dms_menu');
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('Aux_Info', $this);

        $this->load->helper('detail_report_helper');
        try {
            $this->model->check_connection();

            $result = $this->model->get_aux_info_item_current_values($target, $id);
            if(count($result) == 0) {
                $str = "No aux info is defined for this item";
            } else {
                $str = make_detail_report_aux_info_section($result);
            }
            echo $str;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Presents the aux info entry page, for example
     * https://dms2.pnl.gov/aux_info/entry/Cell%20Culture/8875/Shew_QC_110415
     * @param type $target
     * @param type $id
     * @param type $name
     */
    function entry($target, $id, $name = "")
    {
        $this->load->helper('menu');
        $this->_set_aux_info_names($target, $id);
        $data['ais'] = $this->aux_info_support;

        // nav_bar setup
        $this->menu = model('App\Models\dms_menu');
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('Aux_Info', $this);

        if (IsNullOrWhiteSpace($name)) {
            $name = "Unknown";
        }

        // labelling information for view
        $data['title'] = "Aux Info Entry";
        $data['heading'] = "Aux Info Entry";
        $data['target'] = $target;
        $data['id'] = $id;
        $data['name'] = $name;

        try {
            $this->model->check_connection();

            $data['categories']= $this->model->get_aux_info_categories($target);
            $data['aux_info_def'] = $this->model->get_aux_info_def($target);

            // load up data array and call view template
            echo view('special/aux_info_entry', $data);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Make entry form for subcategory items via AJAX ()called by loadItemEntryForm)
     * @param type $target
     */
    function item_values($target)
    {
        $category = $this->input->post('category');
        $subcategory = $this->input->post('subcategory');
        $id = $this->input->post('id');
        $this->_set_aux_info_names($target, $id);
        try {
            $this->model->check_connection();
            list($ai_items, $ai_choices) = $this->model->get_aux_info($target, $category, $subcategory, $id);
            echo $this->aux_info_support->make_item_entry_form($ai_items, $ai_choices);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Update database (from AJAX call)
     */
    function update()
    {
        $fields = $this->model->get_field_validation_fields();

        // Get expected field values from POST
        $parmObj = new stdClass();
        foreach(array_keys($fields) as $name) {
            $parmObj->$name = isset($_POST[$name])?$_POST[$name]:'';
        }

        // Collect the item names and item values into delimited lists
        // The delimiter is !
        $fieldNames = '';
        foreach($parmObj->FieldNamesEx as $field) {
            $fieldNames .= str_replace('!', '&#33;', $field) . '!';
        }
        $parmObj->FieldNamesEx = $fieldNames;

        $fieldValues = '';
        foreach($parmObj->FieldValuesEx as $value) {
            $fieldValues .= str_replace('!', '&#33;', $value) . '!';
        }
        $parmObj->FieldValuesEx = $fieldValues;

        $message = "";
        $result = $this->model->add_or_update($parmObj, "add", $message);
        if($result != 0) {
            echo "($result):$message";
        } else {
            echo "Update was successful";
        }
    }
}
?>
