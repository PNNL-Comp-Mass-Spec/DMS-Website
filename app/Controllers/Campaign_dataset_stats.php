<?php
namespace App\Controllers;

class Campaign_dataset_stats extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "campaign_dataset_stats";
        $this->my_title = "Campaign Dataset Stats";
    }
}
?>
