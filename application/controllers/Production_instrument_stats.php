<?php
require("Base_controller.php");

class Production_instrument_stats extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "production_instrument_stats";
        $this->my_title = "Dataset Production Statistics";
    }

}
?>
