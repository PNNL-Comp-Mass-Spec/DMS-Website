<?php
namespace App\Controllers;

class Residue extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "residue";
        $this->my_title = "Residue";
    }
}
?>
