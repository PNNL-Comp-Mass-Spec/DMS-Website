<?php
namespace App\Controllers;

/**
 * This class is used to copy Aux info data from one entity to another.
 * For example, on https://dms2.pnl.gov/aux_info/entry/Experiment/214930/QC_Shew_18_01
 * see the Copy Info textbox and Copy button
 */
class Aux_info_copy extends BaseController {

    /**
     * Constructor
     */
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "aux_info_copy";
        $this->my_model = "M_aux_info_copy";
        $this->my_title = "Aux Info Copy";
        $this->my_create_action = "aux_info_copy/create";
        $this->my_edit_action = "aux_info_copy/edit";

        $this->helpers = array_merge($this->helpers, ['url', 'text', 'form']);
        $this->model = model('App\\Models\\'.$this->my_model);

        $this->aux_info_support = new \App\Libraries\Aux_info_support();
    }

    /**
     * Update database (from AJAX call)
     */
    function update()
    {
        $fields = $this->model->get_field_validation_fields();

        // get expected field values from POST
        $parmObj = new stdClass();
        foreach(array_keys($fields) as $name) {
            $parmObj->$name = isset($_POST[$name])?$_POST[$name]:'';
        }
        $command = $this->input->post('CopyMode');

        $message = "";
        $result = $this->model->add_or_update($parmObj, $command, $message);
        if($result != 0) {
            echo "($result):$message";
        } else {
            echo "Update was successful";
        }
    }
}
?>
