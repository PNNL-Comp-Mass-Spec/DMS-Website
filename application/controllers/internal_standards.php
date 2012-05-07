<?php
require("base_controller.php");

class internal_standards extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "internal_standards";
		$this->my_title = "Internal Standards";
	}

}
?>