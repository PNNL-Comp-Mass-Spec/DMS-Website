<?php
namespace App\Controllers;

class Usage_reporting extends Base_controller {
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
