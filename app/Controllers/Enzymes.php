<?php
namespace App\Controllers;

class Enzymes extends DmsBase {
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
