<?php
require("base_controller.php");

class experiment_group_members extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "experiment_group_members";
		$this->my_title = "Experiment Group Members";
	}

}
?>