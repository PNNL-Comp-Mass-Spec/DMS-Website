<?php

class Aux_info_copy extends CI_Controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "aux_info_copy";
        $this->my_model = "M_aux_info_copy";
        $this->my_title = "Aux Info Copy";
        $this->my_create_action = "aux_info_copy/create";
        $this->my_edit_action = "aux_info_copy/edit";

        $this->load->helper(array('url', 'string', 'form'));
        $this->load->model($this->my_model, 'model', TRUE);

        $this->load->library('aux_info_support');
    }

    // --------------------------------------------------------------------
    // --------------------------------------------------------------------
    // update database (from AJAX call)
    function update()
    {
        $fields = $this->model->get_field_validation_fields();

        // get expected field values from POST
        $obj = new stdClass();
        foreach(array_keys($fields) as $name) {
            $obj->$name = isset($_POST[$name])?$_POST[$name]:'';
        }
        $command = $this->input->post('CopyMode');

        $message = "";
        $result = $this->model->add_or_update($obj, $command, $message);
        if($result != 0) {
            echo "($result):$message";
        } else {
            echo "Update was successful";
        }
    }

}
?>
