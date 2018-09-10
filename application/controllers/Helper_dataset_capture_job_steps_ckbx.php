<?php
require("Base_controller.php");

class Helper_dataset_capture_job_steps_ckbx extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_dataset_capture_job_steps_ckbx";
        $this->my_title = "Dataset Capture Job";
    }
}
?>
