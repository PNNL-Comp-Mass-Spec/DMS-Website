<?php
require("Base_controller.php");

class helper_experimentid extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_experimentid";
        $this->my_title = "Experiment Helper";
    }
}


?>