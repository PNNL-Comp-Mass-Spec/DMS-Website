<?php
namespace App\Controllers;

class Maxquant_mods extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "maxquant_mods";
        $this->my_title = "MaxQuant Mods";
    }
}
?>
