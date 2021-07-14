<?php
namespace App\Controllers;

class Mts_pt_db_jobs extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "mts_pt_db_jobs";
        $this->my_title = "MTS PT DB Jobs";
    }
}
?>
