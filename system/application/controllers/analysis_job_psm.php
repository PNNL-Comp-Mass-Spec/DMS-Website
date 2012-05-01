<?php
require("base_controller.php");

class analysis_job_psm extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "analysis_job_psm";
		$this->my_title = "Analysis Job PSM";
	}
}


?>