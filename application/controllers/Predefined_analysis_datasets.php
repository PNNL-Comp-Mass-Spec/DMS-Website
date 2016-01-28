<?php
require("Base_controller.php");

class Predefined_analysis_datasets extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "predefined_analysis_datasets";
		$this->my_title = "Datasets For Predefined Analysis";
	}

}
?>