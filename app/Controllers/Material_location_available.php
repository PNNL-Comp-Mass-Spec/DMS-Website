<?php
namespace App\Controllers;

class Material_location_available extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "material_location_available";
        $this->my_title = "Available Material Location";
    }
}
?>
