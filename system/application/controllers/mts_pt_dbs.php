<?php
require("base_controller.php");

class mts_pt_dbs extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "mts_pt_dbs";
		$this->my_title = "MTS Peptide";
	}
}
?>