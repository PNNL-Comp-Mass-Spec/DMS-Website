<?php
namespace App\Controllers;

class Sample_prep_request_active extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "sample_prep_request_active";
        $this->my_title = "Active Sample Prep Requests";
    }
}
?>
