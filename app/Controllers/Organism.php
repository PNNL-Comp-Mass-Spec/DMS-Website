<?php
namespace App\Controllers;

class Organism extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "organism";
        $this->my_title = "Organism";
    }
}
?>
