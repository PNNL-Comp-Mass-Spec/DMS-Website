<?php
require("base_controller.php");

class data_package_campaigns extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "data_package_campaigns";
		$this->my_title = "Data Package Campaigns";
	}
}


?>