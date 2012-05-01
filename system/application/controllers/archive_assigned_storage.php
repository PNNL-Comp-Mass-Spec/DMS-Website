<?php
require("base_controller.php");

class archive_assigned_storage extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "archive_assigned_storage";
		$this->my_title = "Archive Assigned Storage";
	}

}
?>