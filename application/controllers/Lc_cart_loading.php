<?php
require("Base_controller.php");

class Lc_cart_loading extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "lc_cart_loading";
        $this->my_title = "LC Cart Loading";
    }
}
?>
