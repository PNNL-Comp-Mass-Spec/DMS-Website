<?php
require("Base_controller.php");

class Smaqc_metrics extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "smaqc_metrics";
        $this->my_title = "SMAQC Metrics";
    }
}


?>
