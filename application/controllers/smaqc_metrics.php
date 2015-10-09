<?php
require("base_controller.php");

class smaqc_metrics extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "smaqc_metrics";
		$this->my_title = "SMAQC Metrics";
	}
}


?>