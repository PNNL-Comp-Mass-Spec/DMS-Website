<?php
require("Base_controller.php");

class freezers extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "freezers";
        $this->my_title = "Freezer";
    }
}


?>
