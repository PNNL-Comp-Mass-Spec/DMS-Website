<?php
require("Base_controller.php");

class Dump_metadata_for_multiple_datasets extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dump_metadata_for_multiple_datasets";
        $this->my_title = "Dump Metadata For Multiple Datasets";
    }

}
?>
