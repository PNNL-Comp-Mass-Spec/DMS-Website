<?php
require("Base_controller.php");

class experimentid extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "experimentid";
        $this->my_title = "Experiment ID";
    }
}


?>