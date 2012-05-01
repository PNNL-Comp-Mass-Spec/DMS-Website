<?php
require("base_controller.php");

class ai_user_funded_datasets_list_report extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "ai_user_funded_datasets_list_report";
		$this->my_title = "User Funded Datasets";
	}

}
?>