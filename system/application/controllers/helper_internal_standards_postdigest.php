<?php
require("base_controller.php");

class helper_internal_standards_postdigest extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_internal_standards_postdigest";
		$this->my_title = "Internal standards for postdigest";
	}

}
?>