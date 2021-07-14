<?php
namespace App\Controllers;

class Instrument_actual extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrument_actual";
        $this->my_title = "Instrument Actual Usage";
    }
}
?>
