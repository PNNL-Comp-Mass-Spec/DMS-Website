<?php
require("base_controller.php");

class tracking_dataset extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "tracking_dataset";
		$this->my_title = "Tracking Dataset";
	}
}


?>