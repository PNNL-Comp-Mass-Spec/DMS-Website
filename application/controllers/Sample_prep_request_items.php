<?php
require("Base_controller.php");

class Sample_prep_request_items extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "sample_prep_request_items";
		$this->my_title = "Sample Prep Request Items";
	}

}
?>