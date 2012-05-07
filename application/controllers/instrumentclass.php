<?php
require("base_controller.php");

class instrumentclass extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "instrumentclass";
		$this->my_title = "Instrument Class";
	}

}
?>