<?php
require("Base_controller.php");

class Helper_campaign extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_campaign";
        $this->my_title = "Campaign Helper";
    }

}
?>
