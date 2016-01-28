<?php
require("Base_controller.php");

class Dataset_daily_check extends Base_controller {


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