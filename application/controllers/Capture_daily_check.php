<?php
require("Base_controller.php");

class Capture_daily_check extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "capture_daily_check";
		$this->my_title = "Capture Daily Check";
	}
}
?>