<?php
require("Base_controller.php");

class Helper_eus_proposal extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_eus_proposal";
        $this->my_title = "EMSL Proposal Helper";
    }

}
?>
