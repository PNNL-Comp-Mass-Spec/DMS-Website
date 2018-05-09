<?php
require("Base_controller.php");

class reference_compound extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "reference_compound";
		$this->my_title = "Reference Compound";
	}
}


?>