<?php
namespace App\Controllers;

class Predefined_analysis_scheduling_queue extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "predefined_analysis_scheduling_queue";
        $this->my_title = "Predefined Analysis Scheduling Queue";
    }
}
?>
