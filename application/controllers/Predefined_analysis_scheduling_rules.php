<?php
require("Base_controller.php");

class Predefined_analysis_scheduling_rules extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "predefined_analysis_scheduling_rules";
        $this->my_title = "Predefined Analysis Scheduling Rules";
    }

}
?>
