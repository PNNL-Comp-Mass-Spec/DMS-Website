<?php
require("base_controller.php");

class experiment_stats extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "experiment_stats";
		$this->my_title = "Experiment Stats";
	}

}
?>