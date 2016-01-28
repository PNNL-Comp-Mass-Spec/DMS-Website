<?php
require("Base_controller.php");

class Sample_prep_request extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "sample_prep_request";
		$this->my_title = "Sample Prep Request";

	}

}
?>