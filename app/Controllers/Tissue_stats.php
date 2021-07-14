<?php
namespace App\Controllers;

class Tissue_stats extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "tissue_stats";
        $this->my_title = "Tissue Usage Statistics";
    }
}
?>
