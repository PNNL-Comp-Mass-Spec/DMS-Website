<?php
require("base_controller.php");

class factors extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "factors";
		$this->my_title = "Factors";
	}
}
?>