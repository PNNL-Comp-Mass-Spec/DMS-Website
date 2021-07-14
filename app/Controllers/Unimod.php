<?php
namespace App\Controllers;

class Unimod extends DmsBase {
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
