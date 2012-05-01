<?php
require("base_controller.php");

class bogus extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "bogus";
		$this->my_title = "Bogus";
	}
}
?>