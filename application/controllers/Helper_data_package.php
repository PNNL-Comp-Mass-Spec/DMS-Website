<?php
require("Base_controller.php");

class Helper_data_package extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_data_package";
		$this->my_title = "Data Package Helper";
	}

}
?>