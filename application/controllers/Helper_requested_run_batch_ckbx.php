<?php
require("Base_controller.php");

class Helper_requested_run_batch_ckbx extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_requested_run_batch_ckbx";
        $this->my_title = "Requested Run Batches";
    }
}
?>
