<?php
require("Base_controller.php");

class Eus_users extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "eus_users";
		$this->my_title = "EUS Users";
	}

}
?>