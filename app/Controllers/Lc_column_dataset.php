<?php
require("Base_controller.php");

class Lc_column_dataset extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "lc_column_dataset";
        $this->my_title = "LC Column Datasets";
    }

}
?>
