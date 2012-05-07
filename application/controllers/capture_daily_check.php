<?php
require("base_controller.php");

class capture_daily_check extends Base_controller {
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