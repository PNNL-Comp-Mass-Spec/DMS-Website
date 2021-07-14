<?php
namespace App\Controllers;

class Instrumentid extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrumentid";
        $this->my_title = "Instrument ID";
    }
}
?>
