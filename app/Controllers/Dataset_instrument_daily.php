<?php
namespace App\Controllers;

class Dataset_instrument_daily extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_instrument_daily";
        $this->my_title = "Dataset Daily Totals By Instrument";
    }
}
?>
