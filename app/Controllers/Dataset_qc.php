<?php
namespace App\Controllers;

class Dataset_qc extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_qc";
        $this->my_title = "Dataset QC";
    }
}
?>
