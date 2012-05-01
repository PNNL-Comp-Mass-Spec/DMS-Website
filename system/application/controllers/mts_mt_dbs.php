<?php
require("base_controller.php");

class mts_mt_dbs extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "mts_mt_dbs";
		$this->my_title = "MTS AMT Tag";
	}
}
?>