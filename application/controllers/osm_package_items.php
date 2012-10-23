<?php
require("base_controller.php");

class osm_package_items extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "osm_package_items";
		$this->my_title = "OSM Package Items";
	}
}


?>