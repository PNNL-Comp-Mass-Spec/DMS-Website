<?php
require("Base_controller.php");

class datasetid extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "datasetid";
        $this->my_title = "Dataset ID";
    }
}


?>
