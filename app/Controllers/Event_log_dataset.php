<?php
namespace App\Controllers;

class Event_log_dataset extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "event_log_dataset";
        $this->my_title = "Dataset Event Log";
    }
}
?>
