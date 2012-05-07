<?php
require("base_controller.php");

class run_planning extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "run_planning";
		$this->my_title = "Run Planning";
	}

}
?>