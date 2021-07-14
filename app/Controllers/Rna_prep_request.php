<?php
namespace App\Controllers;

class Rna_prep_request extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "rna_prep_request";
        $this->my_title = "RNA Prep Request";
    }
}
?>
