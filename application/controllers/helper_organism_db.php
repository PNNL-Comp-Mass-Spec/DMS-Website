<?php
require("base_controller.php");

class helper_organism_db extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_organism_db";
		$this->my_title = "Organism DB Helper";
	}
}


?>