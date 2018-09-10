<?php
require("Base_controller.php");

class Capture_multi_job_update extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "capture_multi_job_update";
        $this->my_title = "Multi Capture Job Update";
    }
}
?>
