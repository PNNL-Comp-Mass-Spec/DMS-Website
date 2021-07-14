<?php
namespace App\Controllers;

class Eus_proposals extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "eus_proposals";
        $this->my_title = "EUS Proposals";
    }
}
?>
