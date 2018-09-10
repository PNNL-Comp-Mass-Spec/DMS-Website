<?php
require("Base_controller.php");

class Helper_dataset extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_dataset";
        $this->my_title = "Dataset Helper";
    }

}
?>
