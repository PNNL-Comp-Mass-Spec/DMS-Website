<?php
namespace App\Controllers;

class Helper_experiment_group extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_experiment_group";
        $this->my_title = "Experiment Group Helper";
    }
}
?>
