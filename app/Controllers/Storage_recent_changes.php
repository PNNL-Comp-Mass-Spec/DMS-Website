<?php
require("Base_controller.php");

class Storage_recent_changes extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "storage_recent_changes";
        $this->my_title = "Storage Recent Changes";
    }

}
?>
