<?php
require("base_controller.php");

class data_package_items extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "data_package_items";
		$this->my_title = "Data Package Items";
	}
}
?>