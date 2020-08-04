<?php
require("Base_controller.php");

class reporter_ion_observation_rate extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "reporter_ion_observation_rate";
        $this->my_title = "Reporter Ion Observation Rate";
    }
}


?>