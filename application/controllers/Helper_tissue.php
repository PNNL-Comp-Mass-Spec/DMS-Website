<?php
require("Base_controller.php");

class helper_tissue extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_tissue";
		$this->my_title = "Tissue Helper";
	}
}


?>