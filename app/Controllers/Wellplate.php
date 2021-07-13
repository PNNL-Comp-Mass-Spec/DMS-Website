<?php
namespace App\Controllers;

class Wellplate extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "wellplate";
        $this->my_title = "Wellplate";
    }
}
?>
