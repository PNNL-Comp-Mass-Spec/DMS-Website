<?php
require("Base_controller.php");

class Dataset_disposition extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "dataset_disposition";
		$this->my_title = "Dataset Disposition";
	}

}
?>