
<?php
require("Base_controller.php");

class Experiment_fraction extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "experiment_fraction";
		$this->my_title = "Experiment Fractions";
	}

}
?>