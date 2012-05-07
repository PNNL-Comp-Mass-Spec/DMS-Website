<?php
require("base_controller.php");

class helper_analysis_processor_tool extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_analysis_processor_tool";
		$this->my_title = "Analysis Processor Tool";
	}

}
?>