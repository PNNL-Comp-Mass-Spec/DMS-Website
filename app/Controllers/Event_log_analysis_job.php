<?php
namespace App\Controllers;

class Event_log_analysis_job extends DmsBase {
    function __construct()
    {
        $this->my_tag = "event_log_analysis_job";
        $this->my_title = "Analysis Job Event Log";
    }
}
?>
