<?php
require("base_controller.php");

class lc_cart_request_loading extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "lc_cart_request_loading";
		$this->my_title = "LC Cart Requested Run Loading";
	}
}
?>