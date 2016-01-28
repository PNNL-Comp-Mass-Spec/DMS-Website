<?php
require("Base_controller.php");

class Archive extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "archive";
		$this->my_title = "Archive";
	}

}
?>