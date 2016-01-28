<?php
require("Base_controller.php");

class Wellplate extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "wellplate";
		$this->my_title = "Wellplate";

	}

}
?>