<?php
namespace App\Controllers;

class Dms_activity extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dms_activity";
        $this->my_title = "DMS Activity";
    }
}
?>
