<?php
namespace App\Controllers;

class Prep_lc_run extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "prep_lc_run";
        $this->my_title = "Sample Prep LC Run";
    }
}
?>
