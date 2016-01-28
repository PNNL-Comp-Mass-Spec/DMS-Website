<?php
require("Base_controller.php");

class Data_package_experiments extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "data_package_experiments";
		$this->my_title = "Data Package Experiments";
	}
}
?>