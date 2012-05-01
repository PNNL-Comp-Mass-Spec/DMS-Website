<?php
require("base_controller.php");

class lc_column extends Base_controller {


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