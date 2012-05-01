<?php
require("base_controller.php");

class dataset_daily_check extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "dataset_daily_check";
		$this->my_title = "Dataset Daily Check Report";
	}

}
?>