<?php
require("base_controller.php");

class dataset_scans extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "dataset_scans";
		$this->my_title = "Dataset Scans";
	}
}
?>