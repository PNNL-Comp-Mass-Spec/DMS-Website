<?php
require("Base_controller.php");

class Dataset_instrument extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_instrument";
        $this->my_title = "Dataset Instrument";
    }

}
?>
