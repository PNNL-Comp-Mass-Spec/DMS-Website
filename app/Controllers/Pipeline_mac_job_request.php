<?php
namespace App\Controllers;

class Pipeline_mac_job_request extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "pipeline_mac_job_request";
        $this->my_title = "Pipeline MAC Job Request";
    }
}
?>
