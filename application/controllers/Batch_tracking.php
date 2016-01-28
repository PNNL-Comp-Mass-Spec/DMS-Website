<?php
require("Base_controller.php");

class Batch_tracking extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "batch_tracking";
		$this->my_title = "Batch Tracking";
	}
}
?>