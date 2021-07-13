<?php
namespace App\Controllers;

class Enzymes extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "enzymes";
        $this->my_title = "Enzymes";
    }
}
?>
