<?php
namespace App\Controllers;

class Sample_submission extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "sample_submission";
        $this->my_title = "Sample Submission";
    }
}
?>
