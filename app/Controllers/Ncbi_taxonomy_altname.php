<?php
namespace App\Controllers;

class Ncbi_taxonomy_altname extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "ncbi_taxonomy_altname";
        $this->my_title = "NCBI Taxonomy AltName";
    }
}
?>
