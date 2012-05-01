<?php
require("base_controller.php");

class helper_material_container extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_material_container";
		$this->my_title = "Choose Material Container";
	}

}
?>