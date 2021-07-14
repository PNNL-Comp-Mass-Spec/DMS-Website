<?php
namespace App\Controllers;

class Analysis_job extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "analysis_job";
        $this->my_title = "Analysis Job";
    }
}
?>
