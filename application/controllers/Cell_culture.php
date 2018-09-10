<?php
require("Base_controller.php");

class Cell_culture extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "cell_culture";
        $this->my_title = "Cell Culture";
    }

}
?>
