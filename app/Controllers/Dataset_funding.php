<?php
namespace App\Controllers;

class Dataset_funding extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_funding";
        $this->my_title = "Dataset Funding";
    }
}
?>
