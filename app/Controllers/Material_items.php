<?php
namespace App\Controllers;

class Material_items extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "material_items";
        $this->my_title = "Material Items";
    }
}
?>
