<?php
namespace App\Controllers;

class Dms_activity extends Base_controller {
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
