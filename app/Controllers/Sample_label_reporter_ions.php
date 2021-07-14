<?php
namespace App\Controllers;

class Sample_label_reporter_ions extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "sample_label_reporter_ions";
        $this->my_title = "Sample Label Reporter Ions";
    }
}
?>
