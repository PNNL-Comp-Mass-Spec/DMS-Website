<?php
namespace App\Controllers;

class Dataset extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset";
        $this->my_title = "Dataset";
    }
}
?>
