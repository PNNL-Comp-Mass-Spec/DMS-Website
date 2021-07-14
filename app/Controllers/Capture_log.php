<?php
namespace App\Controllers;

class Capture_log extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "capture_log";
        $this->my_title = "Capture Log";
    }
}
?>
