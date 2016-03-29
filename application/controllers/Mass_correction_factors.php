<?php
require("Base_controller.php");

class mass_correction_factors extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "mass_correction_factors";
		$this->my_title = "Mass Correction Factors";
	}
}


?>