<?php
namespace App\Controllers;

class Archive_daily_check extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "archive_daily_check";
        $this->my_title = "Archive Daily Check Report";
    }
}
?>
