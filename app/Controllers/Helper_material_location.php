<?php
namespace App\Controllers;

class Helper_material_location extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_material_location";
        $this->my_title = "Material Location";
    }
}
?>
