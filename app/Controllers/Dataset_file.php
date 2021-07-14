<?php
namespace App\Controllers;

class Dataset_file extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_file";
        $this->my_title = "Dataset File";
    }
}
?>
