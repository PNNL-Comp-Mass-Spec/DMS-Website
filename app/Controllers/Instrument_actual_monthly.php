<?php
namespace App\Controllers;

class Instrument_actual_monthly extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrument_actual_monthly";
        $this->my_title = "Instrument Actual Monthly";
    }
}
?>
