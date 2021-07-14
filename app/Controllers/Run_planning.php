<?php
namespace App\Controllers;

class Run_planning extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "run_planning";
        $this->my_title = "Run Planning";
    }
}
?>
