<?php
require("Base_controller.php");

class lc_cart_configuration extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "lc_cart_configuration";
		$this->my_title = "LC Cart Configuration";
	}
}


?>