<?php
require("Base_controller.php");

class Mts_mt_db_jobs extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "mts_mt_db_jobs";
		$this->my_title = "MTS MT DB Jobs";
	}
}
?>