<?php
require("Base_controller.php");

class Ontology extends Base_controller {
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
