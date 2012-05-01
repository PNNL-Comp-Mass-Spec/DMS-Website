<?php
require("base_controller.php");

class prep_lc_capture_job extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "prep_lc_capture_job";
		$this->my_title = "Prep LC Run File Capture";
	}
}
?>