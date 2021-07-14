<?php
namespace App\Controllers;

class Dataset_scans extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_scans";
        $this->my_title = "Dataset Scans";
    }
}
?>
