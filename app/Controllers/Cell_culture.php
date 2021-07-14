<?php
namespace App\Controllers;

class Cell_culture extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "cell_culture";
        $this->my_title = "Cell Culture";
    }
}
?>
