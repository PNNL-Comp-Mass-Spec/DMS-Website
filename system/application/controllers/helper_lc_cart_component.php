<?php
require("base_controller.php");

class helper_lc_cart_component extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_lc_cart_component";
		$this->my_title = "LC Cart Component";
	}

}
?>