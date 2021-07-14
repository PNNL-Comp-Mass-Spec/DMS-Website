<?php
namespace App\Controllers;

class Ontology extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "ontology";
        $this->my_title = "Ontology";
    }
}
?>
