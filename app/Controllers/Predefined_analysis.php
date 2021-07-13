<?php
namespace App\Controllers;

class Predefined_analysis extends Base_controller {
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
