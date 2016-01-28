<?php
require("Grid.php");

class Requested_run_batch_blocking extends Grid {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "requested_run_batch_blocking";
		$this->my_title = "Requested Run Batch Blocking";
	}

	// --------------------------------------------------------------------
	// --------------------------------------------------------------------
	function grid() {
		$save_url = $this->my_tag . '/operation';
		$data_url = $this->my_tag . '/grid_data';
		$this->grid_page('requested_run_grid', $save_url, $data_url);
	}
	// --------------------------------------------------------------------
	function grid_data() {
		$this->grid_data_from_sproc('requested_run_data_sproc', $this->my_tag);
	}

}
?>