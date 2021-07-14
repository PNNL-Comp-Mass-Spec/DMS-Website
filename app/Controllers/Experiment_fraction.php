<?php
namespace App\Controllers;

class Experiment_fraction extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "experiment_fraction";
        $this->my_title = "Experiment Fractions";
    }
}
?>
