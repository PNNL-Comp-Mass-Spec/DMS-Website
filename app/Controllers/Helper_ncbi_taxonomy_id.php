<?php
namespace App\Controllers;

class Helper_ncbi_taxonomy_id extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_ncbi_taxonomy_id";
        $this->my_title = "NCBI Taxonomy ID Helper";
    }
}
?>
