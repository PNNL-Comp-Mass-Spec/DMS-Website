<?php
namespace App\Controllers;

class Run_assignment_wellplate extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "run_assignment_wellplate";
        $this->my_title = "Run Assignment (by wellplate)";
    }
}
?>
