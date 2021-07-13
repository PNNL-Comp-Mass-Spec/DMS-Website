<?php
namespace App\Controllers;

class Run_assignment_wellplate extends Base_controller {
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
