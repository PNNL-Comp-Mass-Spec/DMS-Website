<?php
namespace App\Controllers;

class Dataset_pm_and_psm extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_pm_and_psm";
        $this->my_title = "Dataset Peak Matching and PSM Results";
    }
}
?>
