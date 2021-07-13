<?php
require("Base_controller.php");

class Event_log_analysis_job extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "event_log_analysis_job";
        $this->my_title = "Analysis Job Event Log";
    }

}
?>
