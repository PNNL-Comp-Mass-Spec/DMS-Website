<?php
require("base_controller.php");

class sample_prep_request_experiments extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "sample_prep_request_experiments";
		$this->my_title = "Experiments From Sample Prep Request";
	}

}
?>