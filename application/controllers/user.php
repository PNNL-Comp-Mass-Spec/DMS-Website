<?php
require("base_controller.php");

class user extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "user";
		$this->my_title = "Users";
	}

}
?>