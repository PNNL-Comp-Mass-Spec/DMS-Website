<?php
require("Base_controller.php");

class Storage extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "storage";
        $this->my_title = "Storage";
    }

}
?>
