<?php
namespace App\Controllers;

class Protein_collection extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "protein_collection";
        $this->my_title = "Protein Collection";
    }
}
?>
