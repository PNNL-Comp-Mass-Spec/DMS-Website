<?php
require("Base_controller.php");

class eus_usage_type extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "eus_usage_type";
        $this->my_title = "EUS Usage Type";
    }
}


?>