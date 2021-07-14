<?php
namespace App\Controllers;

class Lc_cart extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "lc_cart";
        $this->my_title = "LC Cart";
    }
}
?>
