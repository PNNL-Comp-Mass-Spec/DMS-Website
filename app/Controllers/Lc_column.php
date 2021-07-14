<?php
namespace App\Controllers;

class Lc_column extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "lc_column";
        $this->my_title = "LC Column";
    }
}
?>
