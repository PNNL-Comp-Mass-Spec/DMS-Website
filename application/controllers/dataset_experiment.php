<?php
require("base_controller.php");

class dataset_experiment extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "dataset_experiment";
		$this->my_title = "Dataset Tracking";
	}

}
?>