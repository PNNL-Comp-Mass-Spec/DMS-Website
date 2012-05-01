<?php
require("base_controller.php");

class batch_tracking extends Base_controller {
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