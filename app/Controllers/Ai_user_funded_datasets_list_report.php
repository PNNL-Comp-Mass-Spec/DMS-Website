<?php
namespace App\Controllers;

class Ai_user_funded_datasets_list_report extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "ai_user_funded_datasets_list_report";
        $this->my_title = "User Funded Datasets";
    }
}
?>
