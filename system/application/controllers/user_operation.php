<?php
require("base_controller.php");

class user_operation extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "user_operation";
		$this->my_title = "User Operation";
	}

}
?>