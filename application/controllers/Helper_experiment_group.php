<?php
require("Base_controller.php");

class Helper_experiment_group extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_experiment_group";
		$this->my_title = "Experiment Group Helper";
	}

}
?>