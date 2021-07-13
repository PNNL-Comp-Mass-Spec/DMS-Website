<?php
namespace App\Controllers;

class Eus_proposal_users extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "eus_proposal_users";
        $this->my_title = "EUS Proposal Users";
    }
}
?>
