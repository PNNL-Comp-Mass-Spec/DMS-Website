<?php
require("base_controller.php");

class usage_reporting extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "usage_reporting";
		$this->my_title = "Usage Reporting";

	}

}
?>