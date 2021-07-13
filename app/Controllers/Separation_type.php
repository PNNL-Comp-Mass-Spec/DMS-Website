<?php
require("Base_controller.php");

class separation_type extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "separation_type";
        $this->my_title = "Separation Type";
    }
}


?>