<?php
require("base_controller.php");

class bogus_multi extends Base_controller {

		// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "bogus_multi";
		$this->my_title = "Update Multiple Bogus";
	}
}


?>