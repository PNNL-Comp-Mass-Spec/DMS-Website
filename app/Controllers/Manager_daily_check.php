<?php
namespace App\Controllers;

class Manager_daily_check extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "manager_daily_check";
        $this->my_title = "Manager Daily Check";
    }
}
?>
