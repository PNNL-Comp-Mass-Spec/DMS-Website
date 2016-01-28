<?php
require("Base_controller.php");

class Material_log extends Base_controller {


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