<?php
require("Base_controller.php");

class Mts_pt_db_jobs extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "mts_pt_db_jobs";
		$this->my_title = "MTS PT DB Jobs";
	}
}
?>