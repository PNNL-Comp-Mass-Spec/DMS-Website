<?php
require("base_controller.php");

class main_log extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "main_log";
		$this->my_title = "Main Log";
	}

}
?>