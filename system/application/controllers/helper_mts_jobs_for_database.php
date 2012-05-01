<?php
require("base_controller.php");

class helper_mts_jobs_for_database extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_mts_jobs_for_database";
		$this->my_title = "Jobs for Mass Tag Database";
	}

}
?>