<?php
namespace App\Controllers;

class Dataset_disposition_lite extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_disposition_lite";
        $this->my_title = "Dataset Disposition";
    }
}
?>
