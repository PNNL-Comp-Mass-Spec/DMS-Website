<?php
require("Base_controller.php");

class Helper_experiment extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_experiment";
        $this->my_title = "Experiment Helper";
    }

}
?>
