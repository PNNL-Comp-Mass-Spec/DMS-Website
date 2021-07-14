<?php
namespace App\Controllers;

class Experiment_stats extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "experiment_stats";
        $this->my_title = "Experiment Stats";
    }
}
?>
