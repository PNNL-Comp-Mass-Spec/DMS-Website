<?php
require("Base_controller.php");

class Dataset_scans extends Base_controller {
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