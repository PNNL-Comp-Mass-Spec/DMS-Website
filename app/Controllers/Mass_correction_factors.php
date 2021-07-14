<?php
namespace App\Controllers;

class Mass_correction_factors extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "mass_correction_factors";
        $this->my_title = "Mass Correction Factors";
    }
}
?>
