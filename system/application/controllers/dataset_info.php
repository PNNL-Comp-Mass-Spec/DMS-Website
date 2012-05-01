<?php
require("base_controller.php");

class dataset_info extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "dataset_info";
		$this->my_title = "Dataset Info";
	}
}
?>