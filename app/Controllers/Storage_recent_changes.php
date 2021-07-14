<?php
namespace App\Controllers;

class Storage_recent_changes extends DmsBase {
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
