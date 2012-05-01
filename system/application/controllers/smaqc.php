<?php
require("base_controller.php");

class smaqc extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "smaqc";
		$this->my_title = "SMAQC";
	}
}


?>