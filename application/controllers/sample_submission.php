<?php
require("base_controller.php");

class sample_submission extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "sample_submission";
		$this->my_title = "Sample Submission";
	}
}
?>