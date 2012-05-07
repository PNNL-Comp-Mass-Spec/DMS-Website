<?php
require("base_controller.php");

class helper_aj_param_file extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_aj_param_file";
		$this->my_title = "Parameter File Helper";
	}

}
?>