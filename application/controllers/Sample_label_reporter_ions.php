<?php
require("Base_controller.php");

class sample_label_reporter_ions extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "sample_label_reporter_ions";
        $this->my_title = "Sample Label Reporter Ions";
    }
}


?>