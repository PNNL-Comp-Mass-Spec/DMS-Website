<?php
namespace App\Controllers;

class Dataset_info extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_info";
        $this->my_title = "Dataset Info";
    }
}
?>
