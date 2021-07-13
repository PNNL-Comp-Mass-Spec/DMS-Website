<?php
namespace App\Controllers;

class Bionet extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "bionet";
        $this->my_title = "Bionet Hosts";
    }
}
?>
