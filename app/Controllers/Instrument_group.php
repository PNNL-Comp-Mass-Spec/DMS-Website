<?php
namespace App\Controllers;

class Instrument_group extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrument_group";
        $this->my_title = "Instrument Group";
    }
}
?>
