<?php
require("base_controller.php");

class capture_log extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "capture_log";
		$this->my_title = "Capture Log";
	}
}
?>