<?php
namespace App\Controllers;

class Archive_daily_check_update extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "archive_daily_check_update";
        $this->my_title = "Archive Update Daily Check Report";
    }
}
?>
