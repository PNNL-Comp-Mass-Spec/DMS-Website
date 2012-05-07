<?php
require("base_controller.php");

class run_op_logs extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "run_op_logs";
		$this->my_title = "Operation Logs";

	}

}
?>