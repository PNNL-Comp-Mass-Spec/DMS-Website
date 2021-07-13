<?php
namespace App\Controllers;

class Instrument_config_history extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrument_config_history";
        $this->my_title = "Instrument Configuration History";
    }
}
?>
