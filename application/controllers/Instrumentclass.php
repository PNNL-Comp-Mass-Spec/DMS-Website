<?php
require("Base_controller.php");

class Instrumentclass extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrumentclass";
        $this->my_title = "Instrument Class";
    }

}
?>
