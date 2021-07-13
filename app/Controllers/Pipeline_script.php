<?php
namespace App\Controllers;

class Pipeline_script extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "pipeline_script";
        $this->my_title = "Pipeline Script";
    }
}
?>
