<?php
namespace App\Controllers;

class Internal_standards_comp extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "internal_standards_comp";
        $this->my_title = "Internal Standards Composition";
    }
}
?>
