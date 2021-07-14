<?php
namespace App\Controllers;

class Usage_reporting extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "usage_reporting";
        $this->my_title = "Usage Reporting";
    }
}
?>
