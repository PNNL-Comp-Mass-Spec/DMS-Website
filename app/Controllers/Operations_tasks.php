<?php
namespace App\Controllers;

class Operations_tasks extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "operations_tasks";
        $this->my_title = "Operation Task Queue";
    }
}
?>
