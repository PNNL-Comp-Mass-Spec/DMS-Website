<?php
require("Base_controller.php");

class Operations_tasks extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "operations_tasks";
		$this->my_title = "Operation Task Queue";
	}
}


?>