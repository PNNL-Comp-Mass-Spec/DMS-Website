<?php
require("base_controller.php");

class instrument_actual extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "instrument_actual";
		$this->my_title = "Instrument Actual Usage";

	}


}
?>