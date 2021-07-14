<?php
namespace App\Controllers;

class Statistics_entities_by_year extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "statistics_entities_by_year";
        $this->my_title = "Statistics Entities By Year";
    }
}
?>
