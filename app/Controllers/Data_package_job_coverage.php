<?php
require("Base_controller.php");

class Data_package_job_coverage extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "data_package_job_coverage";
        $this->my_title = "Data Package Job Coverage";
    }
}
?>
