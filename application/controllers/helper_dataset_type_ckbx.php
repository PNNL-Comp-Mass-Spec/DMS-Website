<?php
require("base_controller.php");

class helper_dataset_type_ckbx extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_dataset_type_ckbx";
		$this->my_title = "Dataset Type";
	}
}
?>