<?php
namespace App\Controllers;

class Archive_path extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "archive_path";
        $this->my_title = "Archive Path";
    }
}
?>
