<?php
require("Base_controller.php");

class Dataset_instrument_runtime extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "dataset_instrument_runtime";
		$this->my_title = "Dataset Instrument Runtime";
	}
}
?>