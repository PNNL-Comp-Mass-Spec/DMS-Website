<?php
require("Base_controller.php");

class Experiment extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "experiment";
		$this->my_title = "Experiment";
	}
}
?>