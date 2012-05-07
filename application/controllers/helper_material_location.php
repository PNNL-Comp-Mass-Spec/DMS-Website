<?php
require("base_controller.php");

class helper_material_location extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_material_location";
		$this->my_title = "Material Location";
	}

}
?>