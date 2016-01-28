<?php
require("Base_controller.php");

class Lc_column extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "lc_column";
		$this->my_title = "LC Column";
	}

}
?>