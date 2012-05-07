<?php
require("base_controller.php");

class data_package_dataset_job_coverage extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "data_package_dataset_job_coverage";
		$this->my_title = "Data Package Dataset Job Coverage";
	}
}
?>