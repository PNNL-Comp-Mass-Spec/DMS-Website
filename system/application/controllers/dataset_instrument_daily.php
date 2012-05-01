<?php
require("base_controller.php");

class dataset_instrument_daily extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "dataset_instrument_daily";
		$this->my_title = "Datset Daily Totals By Instrument";
	}

}
?>