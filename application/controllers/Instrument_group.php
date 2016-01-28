<?php
require("Base_controller.php");

class Instrument_group extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "instrument_group";
		$this->my_title = "Instrument Group";
	}
}


?>