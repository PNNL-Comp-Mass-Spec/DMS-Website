<?php
require("base_controller.php");

class dataset extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "dataset";
		$this->my_title = "Dataset";
	}

}
?>