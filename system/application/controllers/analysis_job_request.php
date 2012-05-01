<?php
require("base_controller.php");

class analysis_job_request extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "analysis_job_request";
		$this->my_title = "Analysis Job Request";


	}

}
?>