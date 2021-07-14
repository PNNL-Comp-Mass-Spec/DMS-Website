<?php
namespace App\Controllers;

class Material_log extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "material_log";
        $this->my_title = "Material Log";
    }
}
?>
