<?php
require("Base_controller.php");

class Dataset extends Base_controller {

    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset";
        $this->my_title = "Dataset";
    }

}
?>
