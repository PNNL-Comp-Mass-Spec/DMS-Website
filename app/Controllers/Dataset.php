<?php
namespace App\Controllers;

use App\Controllers;

class Dataset extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset";
        $this->my_title = "Dataset";
    }
}
?>
