<?php
namespace App\Controllers;

class Instrument extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "instrument";
        $this->my_title = "Instrument";
    }
}
?>
