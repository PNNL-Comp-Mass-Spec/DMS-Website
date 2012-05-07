<?php
require("base_controller.php");

class analysis_tools extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "analysis_tools";
		$this->my_title = "Analysis Tools";
	}

}
?>