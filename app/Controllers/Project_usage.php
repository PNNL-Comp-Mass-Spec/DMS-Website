<?php
namespace App\Controllers;

class Project_usage extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "project_usage";
        $this->my_title = "Project Usage Stats";
    }
}
?>
