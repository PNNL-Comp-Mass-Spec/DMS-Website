<?php
namespace App\Controllers;

class Notification_event extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "notification_event";
        $this->my_title = "Notification Event";
    }
}
?>
