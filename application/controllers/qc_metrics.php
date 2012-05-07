<?php
require("base_controller.php");

class qc_metrics extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "qc_metrics";
		$this->my_title = "QC Metrics";
	}
}


?>