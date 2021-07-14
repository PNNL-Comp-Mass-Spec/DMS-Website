<?php
namespace App\Controllers;

class Main_log extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "main_log";
        $this->my_title = "Main Log";
    }
}
?>
