<?php
namespace App\Controllers;

class Dataset_instrument_runtime extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_instrument_runtime";
        $this->my_title = "Dataset Instrument Runtime";
    }
}
?>
