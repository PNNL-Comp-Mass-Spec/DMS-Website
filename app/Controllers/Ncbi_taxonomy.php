<?php
namespace App\Controllers;

class Ncbi_taxonomy extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "ncbi_taxonomy";
        $this->my_title = "NCBI Taxonomy";
    }
}
?>
