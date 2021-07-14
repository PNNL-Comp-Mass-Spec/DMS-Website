<?php
namespace App\Controllers;

class Helper_instrument_dataset_type extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_instrument_dataset_type";
        $this->my_title = "Instrument Allowed Dataset Types Helper";
    }
}
?>
