<?php
require("base_controller.php");

class charge_code extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "charge_code";
		$this->my_title = "Charge Code";
	}
}


?>