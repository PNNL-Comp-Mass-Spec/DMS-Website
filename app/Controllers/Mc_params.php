<?php
namespace App\Controllers;

class Mc_params extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "mc_params";
        $this->my_title = "Manager Control Parameters";
    }
}
?>
