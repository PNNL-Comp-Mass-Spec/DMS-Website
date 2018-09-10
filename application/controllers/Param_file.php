<?php
require("Base_controller.php");

class param_file extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "param_file";
        $this->my_title = "Param File";
    }
}


?>
