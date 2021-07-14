<?php
namespace App\Controllers;

class Requested_run_fraction extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "requested_run_fraction";
        $this->my_title = "Requested Run Fraction";
    }
}
?>
