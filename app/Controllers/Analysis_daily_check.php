<?php
namespace App\Controllers;

class Analysis_daily_check extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "analysis_daily_check";
        $this->my_title = "Analysis Job Daily Check";
    }
}
?>
