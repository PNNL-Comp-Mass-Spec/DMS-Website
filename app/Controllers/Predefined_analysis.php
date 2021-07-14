<?php
namespace App\Controllers;

class Predefined_analysis extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "predefined_analysis";
        $this->my_title = "Predefined Analysis";
    }
}
?>
