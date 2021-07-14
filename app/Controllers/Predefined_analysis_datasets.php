<?php
namespace App\Controllers;

class Predefined_analysis_datasets extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "predefined_analysis_datasets";
        $this->my_title = "Datasets For Predefined Analysis";
    }
}
?>
