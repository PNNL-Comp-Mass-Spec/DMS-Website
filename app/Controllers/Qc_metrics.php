<?php
namespace App\Controllers;

class Qc_metrics extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "qc_metrics";
        $this->my_title = "QC Metrics";
    }
}
?>
