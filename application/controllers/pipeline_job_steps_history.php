<?php
require("base_controller.php");

class pipeline_job_steps_history extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "pipeline_job_steps_history";
		$this->my_title = "Job Steps History";
	}
}


?>