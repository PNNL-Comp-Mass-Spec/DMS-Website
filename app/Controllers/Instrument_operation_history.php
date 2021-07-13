<?php
namespace App\Controllers;

class Instrument_operation_history extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrument_operation_history";
        $this->my_title = "Instrument Operation History";
    }
}
?>
