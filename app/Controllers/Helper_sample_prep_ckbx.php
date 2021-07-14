<?php
namespace App\Controllers;

class Helper_sample_prep_ckbx extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_sample_prep_ckbx";
        $this->my_title = "Sample Prep Request";
    }
}
?>
