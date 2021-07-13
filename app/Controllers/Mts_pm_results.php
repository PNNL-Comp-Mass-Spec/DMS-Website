<?php
namespace App\Controllers;

class Mts_pm_results extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "mts_pm_results";
        $this->my_title = "Peak Matching Results";
    }
}
?>
