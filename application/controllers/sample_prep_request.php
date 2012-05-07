<?php
require("base_controller.php");

class sample_prep_request extends Base_controller {


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