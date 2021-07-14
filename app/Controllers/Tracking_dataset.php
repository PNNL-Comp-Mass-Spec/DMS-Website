<?php
namespace App\Controllers;

class Tracking_dataset extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "tracking_dataset";
        $this->my_title = "Tracking Dataset";
    }
}
?>
