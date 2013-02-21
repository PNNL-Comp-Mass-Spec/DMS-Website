<?php
require("base_controller.php");

class instrument_actual_monthly extends Base_controller {
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
