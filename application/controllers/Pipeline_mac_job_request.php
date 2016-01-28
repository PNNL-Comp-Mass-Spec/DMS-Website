<?php
require("Base_controller.php");

class Pipeline_mac_job_request extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "pipeline_mac_job_request";
		$this->my_title = "Pipeline MAC Job Request";
	}

}
?>