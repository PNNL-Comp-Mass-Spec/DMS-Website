<?php
require("Base_controller.php");

class helper_ncbi_taxonomy_id extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_ncbi_taxonomy_id";
		$this->my_title = "NCBI Taxonomy ID Helper";
	}
}


?>