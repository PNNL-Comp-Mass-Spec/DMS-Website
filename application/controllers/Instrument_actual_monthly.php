<?php
require("Base_controller.php");

class Instrument_actual_monthly extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "instrument_actual_monthly";
		$this->my_title = "Instrument Actual Monthly";
	}
}


?>
