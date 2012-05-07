<?php
require("base_controller.php");

class helper_protein_collection extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_protein_collection";
		$this->my_title = "Protein Collection Name Helper";
	}

}
?>