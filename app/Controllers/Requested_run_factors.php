<?php
namespace App\Controllers;

class Requested_run_factors extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "requested_run_factors";
        $this->my_title = "Requested Run Factors";
    }
}
?>
