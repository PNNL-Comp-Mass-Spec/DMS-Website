<?php
namespace App\Controllers;

class Helper_requested_run_ckbx extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_requested_run_ckbx";
        $this->my_title = "Requested Run Chooser";
    }
}
?>
