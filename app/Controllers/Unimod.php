<?php
namespace App\Controllers;

class Unimod extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "unimod";
        $this->my_title = "Unimod Mods";
    }
}
?>
