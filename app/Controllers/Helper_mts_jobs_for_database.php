<?php
namespace App\Controllers;

class Helper_mts_jobs_for_database extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_mts_jobs_for_database";
        $this->my_title = "Jobs for Mass Tag Database";
    }
}
?>
