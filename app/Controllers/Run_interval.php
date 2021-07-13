<?php
namespace App\Controllers;

class Run_interval extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "run_interval";
        $this->my_title = "Run Interval";
    }
}
?>
