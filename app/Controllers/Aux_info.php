<?php
namespace App\Controllers;

class Aux_info extends BaseController {

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['dms_search', 'cookie', 'user', 'dms_logging', 'url', 'text', 'form', 'string'];

    private $aux_info_support = null;
    private $model = null;

    /**
     * CodeIgniter 4 Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //--------------------------------------------------------------------
        // Preload any models, libraries, etc, here.
        //--------------------------------------------------------------------
        // E.g.:
        // $this->session = \Config\Services::session();

        //Ensure a session is initialized
        $session = \Config\Services::session();

        $preferences = $this->getPreferences();

        $this->help_page_link = config('App')->pwiki;
        $this->help_page_link .= config('App')->wikiHelpLinkPrefix;

///--
        $this->my_tag = "aux_info";
        $this->my_title = "Aux Info";

        $this->model = model('App\\Models\\M_aux_info');

        $this->aux_info_support = new \App\Libraries\Aux_info_support();
    }

    /**
     * Set aux info names
     * @param type $target
     * @param type $id
     */
    private function _set_aux_info_names($target, $id='')
    {
        $this->aux_info_support->item_entry_url = site_url('aux_info/item_values/'.$target);
        $this->aux_info_support->copy_info_url =  site_url('aux_info_copy/update');
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
        helper('menu');

        // nav_bar setup
        $this->menu = model('App\Models\Dms_menu');
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('Aux_Info', $this);

        helper('detail_report_helper');
        try {
            $this->model->check_connection();

            $result = $this->model->get_aux_info_item_current_values($target, $id);
            if(count($result) == 0) {
                $str = "No aux info is defined for this item";
            } else {
                $str = make_detail_report_aux_info_section($result);
            }
            echo $str;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Presents the aux info entry page, for example
     * https://dms2.pnl.gov/aux_info/entry/Biomaterial/8875/Shew_QC_110415
     * @param type $target
     * @param type $id
     * @param type $name
     */
    function entry($target, $id, $name = "")
    {
        helper('menu');
        $this->_set_aux_info_names($target, $id);
        $data['ais'] = $this->aux_info_support;

        // nav_bar setup
        $this->menu = model('App\Models\Dms_menu');
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('Aux_Info', $this);

        if (IsNullOrWhiteSpace($name)) {
            $name = "Unknown";
        }

        // Labelling information for view
        $data['title'] = "Aux Info Entry";
        $data['heading'] = "Aux Info Entry";
        $data['target'] = $target;
        $data['id'] = $id;
        $data['name'] = $name;

        try {
            $this->model->check_connection();

            $data['categories']= $this->model->get_aux_info_categories($target);
            $data['aux_info_def'] = $this->model->get_aux_info_def($target);

            // Load up data array and call view template
            echo view('special/aux_info_entry', $data);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Make entry form for subcategory items via AJAX ()called by loadItemEntryForm)
     * @param type $target
     */
    function item_values($target)
    {
        $category = $this->request->getPost('category');
        $subcategory = $this->request->getPost('subcategory');
        $id = $this->request->getPost('id');
        $this->_set_aux_info_names($target, $id);
        try {
            $this->model->check_connection();
            list($ai_items, $ai_choices) = $this->model->get_aux_info($target, $category, $subcategory, $id);
            echo $this->aux_info_support->make_item_entry_form($ai_items, $ai_choices);
        } catch (\Exception $e) {
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
        $parmObj = new \stdClass();
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
