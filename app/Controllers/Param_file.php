<?php
namespace App\Controllers;

class Param_file extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "param_file";
        $this->my_title = "Param File";
    }
}
?>
