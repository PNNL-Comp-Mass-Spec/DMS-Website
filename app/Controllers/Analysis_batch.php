<?php
namespace App\Controllers;

class Analysis_batch extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "analysis_batch";
        $this->my_title = "Analysis Batch";
    }
}
?>
