<?php
require("Base_controller.php");

class Archive_daily_check extends Base_controller {


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
