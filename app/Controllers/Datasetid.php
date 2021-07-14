<?php
namespace App\Controllers;

class Datasetid extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "datasetid";
        $this->my_title = "Dataset ID";
    }
}
?>
