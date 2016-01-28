<?php
require("Base_controller.php");

class Request_daily extends Base_controller {


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