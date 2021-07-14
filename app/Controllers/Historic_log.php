<?php
namespace App\Controllers;

class Historic_log extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "historic_log";
        $this->my_title = "Historic Log";
    }
}
?>
