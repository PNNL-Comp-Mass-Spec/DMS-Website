<?php
require("base_controller.php");

class requested_run_factors extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "requested_run_factors";
		$this->my_title = "Requested Run Factors";
	}
}
?>