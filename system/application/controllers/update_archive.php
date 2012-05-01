<?php
require("base_controller.php");

class update_archive extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "update_archive";
		$this->my_title = "Update Archive";
	}

}
?>