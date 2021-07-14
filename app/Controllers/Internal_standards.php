<?php
namespace App\Controllers;

class Internal_standards extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "internal_standards";
        $this->my_title = "Internal Standards";
    }
}
?>
