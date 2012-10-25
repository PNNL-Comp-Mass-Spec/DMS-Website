<?php
require("base_controller.php");

class osm_package_files extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "osm_package_files";
		$this->my_title = "OSM Package Files";
	}

}
?>