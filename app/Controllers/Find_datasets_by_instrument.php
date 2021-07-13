<?php
require("Base_controller.php");

class Find_datasets_by_instrument extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "find_datasets_by_instrument";
        $this->my_title = "Datasets By Instrument";
    }

}
?>
