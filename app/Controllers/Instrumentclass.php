<?php
namespace App\Controllers;

class Instrumentclass extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrumentclass";
        $this->my_title = "Instrument Class";
    }
}
?>
