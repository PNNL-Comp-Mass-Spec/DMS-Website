<?php
namespace App\Controllers;

class Helper_data_package extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_data_package";
        $this->my_title = "Data Package Helper";
    }
}
?>
