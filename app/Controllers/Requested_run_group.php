<?php
namespace App\Controllers;

class Requested_run_group extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "requested_run_group";
        $this->my_title = "Requested Run Group";
    }
}
?>
