<?php
require("base_controller.php");

class analysis_log extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "analysis_log";
		$this->my_title = "Analysis Log";
	}

}
?>