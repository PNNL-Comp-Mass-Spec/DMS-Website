<?php
require("base_controller.php");

class analysis_job extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "analysis_job";
		$this->my_title = "Analysis Job";

	}

}
?>