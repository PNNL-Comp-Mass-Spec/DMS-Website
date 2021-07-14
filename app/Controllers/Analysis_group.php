<?php
namespace App\Controllers;

class Analysis_group extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "analysis_group";
        $this->my_title = "Analysis Job Group";
    }
}
?>
