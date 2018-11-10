<?php
require("Base_controller.php");

class experiment_plex_members extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "experiment_plex_members";
        $this->my_title = "Experiment Plex Members";
    }
}


?>