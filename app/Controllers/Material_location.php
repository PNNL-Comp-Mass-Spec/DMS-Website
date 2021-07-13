<?php
namespace App\Controllers;

class Material_location extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "material_location";
        $this->my_title = "Material Location";
    }
}
?>
