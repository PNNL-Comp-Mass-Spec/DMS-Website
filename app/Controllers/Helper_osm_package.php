<?php
require("Base_controller.php");

class Helper_osm_package extends Base_controller {

    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "helper_osm_package";
        $this->my_title = "OSM Package Helper";

    }

}
?>
