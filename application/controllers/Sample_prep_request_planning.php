<?php
require("Base_controller.php");

class sample_prep_request_planning extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "sample_prep_request_planning";
        $this->my_title = "Sample Prep Request Planning";
    }
}


?>