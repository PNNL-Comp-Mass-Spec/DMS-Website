<?php
require("Base_controller.php");

class Manager_daily_check extends Base_controller {

    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "manager_daily_check";
        $this->my_title = "Manager Daily Check";
    }

}
?>
