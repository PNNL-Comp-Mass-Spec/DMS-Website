<?php
require("Base_controller.php");

class Helper_capture_job_steps extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_capture_job_steps";
		$this->my_title = "Capture Job Steps";
	}
}
?>