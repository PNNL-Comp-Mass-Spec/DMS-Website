<?php
namespace App\Controllers;

class Helper_organism_db extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_organism_db";
        $this->my_title = "Organism DB Helper";
    }
}
?>
