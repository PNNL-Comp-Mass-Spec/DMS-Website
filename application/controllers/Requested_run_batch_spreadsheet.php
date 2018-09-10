<?php
require("Base_controller.php");

class Requested_run_batch_spreadsheet extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "requested_run_batch_spreadsheet";
        $this->my_model = "M_requested_run_batch_spreadsheet";
        $this->my_title = "Requested Run Batch Spreadsheet";
        $this->my_create_action = "enter";
        $this->my_edit_action = "enter";
    }

}
?>
