<?php
require("base_controller.php");

class predefined_analysis extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "predefined_analysis";
		$this->my_title = "Predefined Analysis";
	}

}
?>