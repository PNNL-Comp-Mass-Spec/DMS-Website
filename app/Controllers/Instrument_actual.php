<?php
namespace App\Controllers;

class Instrument_actual extends DmsBase {
    function __construct()
    {
        $this->my_tag = "instrument_actual";
        $this->my_title = "Instrument Actual Usage";
    }
}
?>
