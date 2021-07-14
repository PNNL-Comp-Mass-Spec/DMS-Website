<?php
namespace App\Controllers;

class Prep_lc_capture_job extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "prep_lc_capture_job";
        $this->my_title = "Prep LC Run File Capture";
    }
}
?>
