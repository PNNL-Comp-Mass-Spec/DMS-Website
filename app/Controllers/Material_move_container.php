<?php
namespace App\Controllers;

class Material_move_container extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "material_move_container";
        $this->my_title = "Move Containers";
    }
}
?>
