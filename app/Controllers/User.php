<?php
namespace App\Controllers;

class User extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "user";
        $this->my_title = "Users";
    }
}
?>
