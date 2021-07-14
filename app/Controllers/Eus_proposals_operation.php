<?php
namespace App\Controllers;

class Eus_proposals_operation extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "eus_proposals_operation";      $this->my_model = "M_eus_proposals_operation";
        $this->my_title = "EUS Proposals Operation";
    }
}
?>
