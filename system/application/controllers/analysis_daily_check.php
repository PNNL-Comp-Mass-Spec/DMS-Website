<?php
require("base_controller.php");

class analysis_daily_check extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "analysis_daily_check";
		$this->my_title = "Analysis Job Daily Check";
	}

}
?>