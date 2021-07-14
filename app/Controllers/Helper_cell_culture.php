<?php
namespace App\Controllers;

class Helper_cell_culture extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_cell_culture";
        $this->my_title = "Cell Culture Helper";
    }
}
?>
