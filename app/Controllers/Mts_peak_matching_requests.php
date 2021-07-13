<?php
namespace App\Controllers;

class Mts_peak_matching_requests extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "mts_peak_matching_requests";
        $this->my_title = "MTS Peak Matching Requests";
    }
}
?>
