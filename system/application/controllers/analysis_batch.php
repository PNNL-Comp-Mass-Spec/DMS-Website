<?php
require("base_controller.php");

class analysis_batch extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "analysis_batch";
		$this->my_title = "Analysis Batch";
	}

}
?>