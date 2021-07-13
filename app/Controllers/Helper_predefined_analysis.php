<?php
require("Base_controller.php");

class Helper_predefined_analysis extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_predefined_analysis";
        $this->my_title = "Predefined Analysis Rule Helper";
    }

}
?>
