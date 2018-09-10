<?php
require("Base_controller.php");

class Helper_organism extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_organism";
        $this->my_title = "Organism Helper";
    }

}
?>
