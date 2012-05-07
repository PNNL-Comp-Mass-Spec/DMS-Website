<?php
require("base_controller.php");

class prep_lc_column extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "prep_lc_column";
		$this->my_title = "Sample Prep LC Column";

	}

}
?>