<?php
namespace App\Controllers;

class Helper_charge_code extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_charge_code";
        $this->my_title = "Charge Code Helper";
    }
}
?>
