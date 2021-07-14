<?php
namespace App\Controllers;

class Pipeline_job_steps_history extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "pipeline_job_steps_history";
        $this->my_title = "Job Steps History";
    }
}
?>
