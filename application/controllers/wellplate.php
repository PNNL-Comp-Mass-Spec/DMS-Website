<?php
require("base_controller.php");

class wellplate extends Base_controller {


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