<?php
require("Base_controller.php");

class enzymes extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "enzymes";
        $this->my_title = "Enzymes";
    }
}


?>