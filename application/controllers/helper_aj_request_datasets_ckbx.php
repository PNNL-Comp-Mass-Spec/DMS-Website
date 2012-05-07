<?php
require("base_controller.php");

class helper_aj_request_datasets_ckbx extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_aj_request_datasets_ckbx";
		$this->my_title = "Analysis Job Request Datasets";
	}

}
?>