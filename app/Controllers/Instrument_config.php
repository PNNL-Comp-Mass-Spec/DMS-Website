<?php
namespace App\Controllers;

class Instrument_config extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrument_config";
        $this->my_title = "Instrument Configuration";
    }
}
?>
