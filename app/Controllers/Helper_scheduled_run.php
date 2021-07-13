<?php
namespace App\Controllers;

class Helper_scheduled_run extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_scheduled_run";
        $this->my_title = "Scheduled Run Helper";
    }
}
?>
