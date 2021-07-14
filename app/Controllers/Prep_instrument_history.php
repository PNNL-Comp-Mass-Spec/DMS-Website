<?php
namespace App\Controllers;

class Prep_instrument_history extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "prep_instrument_history";
        $this->my_title = "Sample Prep Instrument Maintenance Note";
    }
}
?>
