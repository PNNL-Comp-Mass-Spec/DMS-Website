<?php
require("base_controller.php");

class pipeline_jobs_history extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "pipeline_jobs_history";
		$this->my_title = "Pipeline Jobs History";
	}
}


?>