<?php
require("base_controller.php");

class pipeline_processor_tool_crosstab extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "pipeline_processor_tool_crosstab";
		$this->my_title = "Step Tools";
	}

}
?>