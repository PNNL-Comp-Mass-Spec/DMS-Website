<?php
namespace App\Controllers;

class Dataset_experiment extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_experiment";
        $this->my_title = "Dataset Tracking";
    }
}
?>
