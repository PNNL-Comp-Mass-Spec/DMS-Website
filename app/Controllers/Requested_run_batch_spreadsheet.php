<?php
namespace App\Controllers;

class Requested_run_batch_spreadsheet extends DmsBase {
    function __construct()
    {
        $this->my_tag = "requested_run_batch_spreadsheet";
        $this->my_title = "Requested Run Batch Spreadsheet";
        $this->my_create_action = "enter";
        $this->my_edit_action = "enter";
    }
}
?>
