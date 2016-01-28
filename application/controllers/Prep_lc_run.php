<?php
require("Base_controller.php");

class Prep_lc_run extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "prep_lc_run";
		$this->my_title = "Sample Prep LC Run";

	}

}
?>