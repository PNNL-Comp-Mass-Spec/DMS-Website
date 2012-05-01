<?php
require("base_controller.php");

class predefined_analysis_preview_mds extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "predefined_analysis_preview_mds";
		$this->my_title = "Preview Predefined Analyses";
	}

}
?>