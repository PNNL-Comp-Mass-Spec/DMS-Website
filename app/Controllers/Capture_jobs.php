<?php
namespace App\Controllers;

class Capture_jobs extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "capture_jobs";
        $this->my_title = "Capture Jobs";
    }
}
?>
