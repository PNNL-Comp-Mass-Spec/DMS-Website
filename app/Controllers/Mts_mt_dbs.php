<?php
namespace App\Controllers;

class Mts_mt_dbs extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "mts_mt_dbs";
        $this->my_title = "MTS AMT Tag";
    }
}
?>
