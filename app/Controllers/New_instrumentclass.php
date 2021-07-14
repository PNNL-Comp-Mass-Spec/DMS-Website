<?php
namespace App\Controllers;

class New_instrumentclass extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "new_instrumentclass";
        $this->my_title = "Add New Instrument Class";
    }
}
?>
