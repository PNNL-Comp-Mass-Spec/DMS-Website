<?php
namespace App\Controllers;

class Freezers extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "freezers";
        $this->my_title = "Freezer";
    }
}
?>
