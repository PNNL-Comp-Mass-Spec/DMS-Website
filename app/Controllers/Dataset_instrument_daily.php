<?php
require("Base_controller.php");

class Dataset_instrument_daily extends Base_controller {


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
