<?php
require("Base_controller.php");

class Helper_material_location_available_ckbx extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_material_location_available_ckbx";
		$this->my_title = "Available Location";
	}
}
?>