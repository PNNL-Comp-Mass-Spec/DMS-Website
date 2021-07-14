<?php
namespace App\Controllers;

class Analysis_job_psm extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "analysis_job_psm";
        $this->my_title = "Analysis Job PSM";
    }
}
?>
