<?php
namespace App\Controllers;

class Event_log_archive extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "event_log_archive";
        $this->my_title = "Archive Event Log";
    }
}
?>
