<?php
namespace App\Controllers;

class Request_daily extends DmsBase {
    function __construct()
    {
        $this->my_tag = "request_daily";
        $this->my_title = "Completed Requested Runs Daily Totals";
    }
}
?>
