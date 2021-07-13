<?php
namespace App\Controllers;

class Analysis_request_jobs extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "analysis_request_jobs";
        $this->my_title = "Analysis Request Jobs";
    }
}
?>
