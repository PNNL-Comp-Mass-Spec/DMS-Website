<?php
namespace App\Controllers;

class Dump_metadata_for_multiple_experiments extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dump_metadata_for_multiple_experiments";
        $this->my_title = "Dump Metadata For Multiple Experiments";
    }
}
?>
