<?php
namespace App\Controllers;

class Tissue extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "tissue";
        $this->my_title = "Tissue Ontology";
    }
}
?>
