<?php
require("base_controller.php");

class organism extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "organism";
		$this->my_title = "Organism";
	}

}
?>