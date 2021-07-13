<?php
namespace App\Controllers;

class Capture_job_steps extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "capture_job_steps";
        $this->my_title = "Capture Job Steps";
    }
}
?>
