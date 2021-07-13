<?php
namespace App\Controllers;

class Predefined_analysis_preview extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "predefined_analysis_preview";
        $this->my_title = "Preview Predefined Analyses";
    }
}
?>
