<?php
require("base_controller.php");

class helper_sample_submissions_ckbx extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_sample_submissions_ckbx";
		$this->my_title = "Sample Submissions";
	}

}
?>