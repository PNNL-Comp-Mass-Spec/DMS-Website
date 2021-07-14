<?php
namespace App\Controllers;

class Requested_run_batch extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "requested_run_batch";
        $this->my_title = "Requested Run Batch";
    }
}
?>
