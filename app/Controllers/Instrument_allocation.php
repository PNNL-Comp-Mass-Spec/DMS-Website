<?php
namespace App\Controllers;

class Instrument_allocation extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrument_allocation";
        $this->my_title = "Instrument Allocation";
    }
}
?>
