<?php
namespace App\Controllers;

class Batch_tracking extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "batch_tracking";
        $this->my_title = "Batch Tracking";
    }
}
?>
