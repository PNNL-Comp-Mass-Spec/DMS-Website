<?php
require("base_controller.php");

class helper_mts_mt_databases extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_mts_mt_databases";
		$this->my_title = "Mass Tag Databases";
	}

}
?>