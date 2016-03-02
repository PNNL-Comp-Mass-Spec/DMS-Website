<?php
require("Base_controller.php");

class ncbi_taxonomy extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "ncbi_taxonomy";
		$this->my_title = "NCBI Taxonomy";
	}
}


?>
