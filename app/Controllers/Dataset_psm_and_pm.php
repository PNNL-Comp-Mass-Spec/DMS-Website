<?php
require("Base_controller.php");

class Dataset_psm_and_pm extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_psm_and_pm";
        $this->my_title = "Dataset PSM and Peak Matching Results";
    }
}


?>
