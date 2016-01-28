<?php
require("Base_controller.php");

class Existing_jobs_for_request extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "existing_jobs_for_request";
		$this->my_title = "Existing Jobs For Request";
	}

}
?>