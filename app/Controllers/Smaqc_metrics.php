<?php
namespace App\Controllers;

class Smaqc_metrics extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "smaqc_metrics";
        $this->my_title = "SMAQC Metrics";
    }
}
?>
