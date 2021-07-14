<?php
namespace App\Controllers;

class Experiment extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "experiment";
        $this->my_title = "Experiment";
    }
}
?>
