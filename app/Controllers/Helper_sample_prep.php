<?php
namespace App\Controllers;

class Helper_sample_prep extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_sample_prep";
        $this->my_title = "Sample Prep Helper";
    }
}
?>
