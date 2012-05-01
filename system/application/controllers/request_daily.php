<?php
require("base_controller.php");

class request_daily extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "request_daily";
		$this->my_title = "Completed Requested Runs Daily Totals";
	}

}
?>