<?php
require("Base_controller.php");

class Instrument_allowed_dataset_type extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrument_allowed_dataset_type";
        $this->my_title = "Instrument Group Allowed Dataset Types";
    }

}
?>
