<?php
require("Base_controller.php");

class Pipeline_jobs_raw extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "pipeline_jobs_raw";
		$this->my_title = "Pipeline Jobs";
	}
}


?>