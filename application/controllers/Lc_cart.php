<?php
require("Base_controller.php");

class Lc_cart extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "lc_cart";
        $this->my_title = "LC Cart";
    }

}
?>
