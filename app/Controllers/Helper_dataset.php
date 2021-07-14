<?php
namespace App\Controllers;

class Helper_dataset extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_dataset";
        $this->my_title = "Dataset Helper";
    }
}
?>
