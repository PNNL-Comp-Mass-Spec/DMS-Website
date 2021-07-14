<?php
namespace App\Controllers;

class Helper_experiment extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_experiment";
        $this->my_title = "Experiment Helper";
    }
}
?>
