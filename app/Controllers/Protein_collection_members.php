<?php
namespace App\Controllers;

class Protein_collection_members extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "protein_collection_members";
        $this->my_title = "Protein Collection Members";
    }
}
?>
