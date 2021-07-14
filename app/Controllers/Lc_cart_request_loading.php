<?php
namespace App\Controllers;

class Lc_cart_request_loading extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "lc_cart_request_loading";
        $this->my_title = "LC Cart Requested Run Loading";
    }
}
?>
