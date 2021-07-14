<?php
namespace App\Controllers;

class Sample_prep_request_items extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "sample_prep_request_items";
        $this->my_title = "Sample Prep Request Items";
    }
}
?>
