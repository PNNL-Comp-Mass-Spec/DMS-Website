<?php
require("base_controller.php");

class instrument extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "instrument";
		$this->my_title = "Instrument";
	}

}
?>