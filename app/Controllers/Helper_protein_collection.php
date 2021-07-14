<?php
namespace App\Controllers;

class Helper_protein_collection extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_protein_collection";
        $this->my_title = "Protein Collection Name Helper";
    }
}
?>
