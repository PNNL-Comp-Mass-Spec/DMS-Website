<?php
require("Base_controller.php");

class Sample_prep_request_assignment extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "sample_prep_request_assignment";
		$this->my_title = "Sample Prep Request Assignment";
	}

}
?>