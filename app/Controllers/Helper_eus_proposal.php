<?php
namespace App\Controllers;

class Helper_eus_proposal extends DmsBase {
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
