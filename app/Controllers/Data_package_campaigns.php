<?php
require("Base_controller.php");

class Data_package_campaigns extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "data_package_campaigns";
        $this->my_title = "Data Package Campaigns";
    }
}


?>
