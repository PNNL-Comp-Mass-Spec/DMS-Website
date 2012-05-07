<?php
require("base_controller.php");

class historic_log extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "historic_log";
		$this->my_title = "Historic Log";
	}

}
?>