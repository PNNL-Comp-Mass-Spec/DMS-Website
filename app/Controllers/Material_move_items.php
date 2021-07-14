<?php
namespace App\Controllers;

class Material_move_items extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "material_move_items";
        $this->my_title = "Move Material Items";
    }
}
?>
