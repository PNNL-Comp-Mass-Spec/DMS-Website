<?php
require("grid.php");

class Factors extends Grid {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "factors";
		$this->my_title = "Factors";
	}

	// --------------------------------------------------------------------
	// --------------------------------------------------------------------
	function grid() {
		$save_url = 'requested_run_factors/operation';
		$this->grid_page('grid_factors', $save_url);
	}
	// --------------------------------------------------------------------
	function grid_data() {
		$this->my_tag = "factors";
		$this->grid_data_from_sproc('list_report_sproc', 'requested_run_factors');
	}

}
?>