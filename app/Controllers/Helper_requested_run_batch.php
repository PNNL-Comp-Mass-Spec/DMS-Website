<?php
namespace App\Controllers;

class Helper_requested_run_batch extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_requested_run_batch";
        $this->my_title = "Requested Run Batch";
    }
}
?>
