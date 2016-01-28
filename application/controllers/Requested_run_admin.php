<?php
require("Base_controller.php");

class Requested_run_admin extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "requested_run_admin";
		$this->my_title = "Requested Run Admin";
	}
}
?>