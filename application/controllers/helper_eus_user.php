<?php
require("base_controller.php");

class helper_eus_user extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_eus_user";
		$this->my_title = "EMSL User Helper";
	}
}


?>