<?php
require("base_controller.php");

class dataset_disposition_lite extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "dataset_disposition_lite";
		$this->my_title = "Dataset Disposition";
	}
}


?>