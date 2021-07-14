<?php
namespace App\Controllers;

class Experimentid extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "experimentid";
        $this->my_title = "Experiment ID";
    }
}
?>
