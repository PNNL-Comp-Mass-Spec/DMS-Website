<?php
require("Base_controller.php");

class Event_log_archive extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "event_log_archive";
		$this->my_title = "Archive Event Log";
	}

}
?>