<?php
require("Base_controller.php");

class Helper_aj_settings_file extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_aj_settings_file";
		$this->my_title = "Settings File Helper";
	}

}
?>