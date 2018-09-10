<?php
require("Base_controller.php");

class Dataset_experiment extends Base_controller {


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
