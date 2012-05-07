<?php
require("base_controller.php");

class helper_experiment extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_experiment";
		$this->my_title = "Experiment Helper";
	}

}
?>