<?php
require("base_controller.php");

class staff_roles extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "staff_roles";
		$this->my_title = "Staff Roles";
	}
}
?>