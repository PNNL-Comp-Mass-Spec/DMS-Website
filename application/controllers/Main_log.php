<?php
require("Base_controller.php");

class Main_log extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "main_log";
        $this->my_title = "Main Log";
    }

}
?>
