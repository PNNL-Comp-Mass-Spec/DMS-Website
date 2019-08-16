<?php
require("Base_controller.php");

class Instrumentid extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrumentid";
        $this->my_title = "Instrument ID";
    }

}
?>
