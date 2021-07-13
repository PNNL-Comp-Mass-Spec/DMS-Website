<?php
require("Base_controller.php");

class Analysis_job_processor_group_membership extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "analysis_job_processor_group_membership";
        $this->my_title = "Analysis Job Processor Group Membership";
    }

}
?>
