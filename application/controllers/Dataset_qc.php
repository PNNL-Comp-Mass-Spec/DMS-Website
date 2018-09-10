<?php
require("Base_controller.php");

class Dataset_qc extends Base_controller {
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
