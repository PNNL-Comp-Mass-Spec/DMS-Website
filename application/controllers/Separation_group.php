<?php
require("Base_controller.php");

class separation_group extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "separation_group";
		$this->my_title = "Separation Group";
	}
}


?>