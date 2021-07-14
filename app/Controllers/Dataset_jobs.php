<?php
namespace App\Controllers;

class Dataset_jobs extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_jobs";
        $this->my_title = "Dataset Jobs";
    }
}
?>
