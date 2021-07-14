<?php
namespace App\Controllers;

class Custom_factors extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "custom_factors";
        $this->my_title = "Custom Factors";
    }
}
?>
