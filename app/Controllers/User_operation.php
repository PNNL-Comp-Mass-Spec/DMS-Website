<?php
namespace App\Controllers;

class User_operation extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "user_operation";
        $this->my_title = "User Operation";
    }
}
?>
