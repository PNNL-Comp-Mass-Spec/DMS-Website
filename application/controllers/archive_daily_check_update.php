<?php
require("base_controller.php");

class archive_daily_check_update extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "archive_daily_check_update";
		$this->my_title = "Archive Update Daily Check Report";
	}

}
?>