<?php
namespace App\Controllers;

class Campaign extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "campaign";
        $this->my_title = "Campaign";
    }
}
?>
