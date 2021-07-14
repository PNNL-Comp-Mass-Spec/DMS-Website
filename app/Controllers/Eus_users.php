<?php
namespace App\Controllers;

class Eus_users extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "eus_users";
        $this->my_title = "EUS Users";
    }
}
?>
