<?php
require("Base_controller.php");

class residue extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "residue";
        $this->my_title = "Residue";
    }
}


?>