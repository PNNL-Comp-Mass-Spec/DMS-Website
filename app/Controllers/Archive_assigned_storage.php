<?php
namespace App\Controllers;

class Archive_assigned_storage extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "archive_assigned_storage";
        $this->my_title = "Archive Assigned Storage";
    }
}
?>
