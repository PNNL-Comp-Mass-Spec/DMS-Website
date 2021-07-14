<?php
namespace App\Controllers;

class Charge_code extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "charge_code";
        $this->my_title = "Charge Code";
    }
}
?>
