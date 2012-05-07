<?php
require("base_controller.php");

class material_log extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "material_log";
		$this->my_title = "Material Log";
	}

}
?>