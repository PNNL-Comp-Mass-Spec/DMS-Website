<?php
require("base_controller.php");

class dataset_funding extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "dataset_funding";
		$this->my_title = "Dataset Funding";
	}

}
?>