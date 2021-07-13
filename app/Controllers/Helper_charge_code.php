<?php
require("Base_controller.php");

class Helper_charge_code extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_charge_code";
        $this->my_title = "Charge Code Helper";
    }
}


?>
