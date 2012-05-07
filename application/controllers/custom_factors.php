<?php
require("base_controller.php");

class custom_factors extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "custom_factors";
		$this->my_title = "Custom Factors";
	}
}


?>