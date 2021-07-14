<?php
namespace App\Controllers;

class Request_daily extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "request_daily";
        $this->my_title = "Completed Requested Runs Daily Totals";
    }
}
?>
