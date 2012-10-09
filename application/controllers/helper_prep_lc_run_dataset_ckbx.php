<?php
require("base_controller.php");

class helper_prep_lc_run_dataset_ckbx extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_prep_lc_run_dataset_ckbx";
		$this->my_title = "HPLC Run Dataset";
	}

}
?>