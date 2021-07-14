<?php
namespace App\Controllers;

class Dataset_disposition extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_disposition";
        $this->my_title = "Dataset Disposition";
    }
}
?>
