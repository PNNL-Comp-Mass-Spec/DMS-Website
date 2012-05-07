<?php
require("base_controller.php");

class statistics_entities_by_year extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "statistics_entities_by_year";
		$this->my_title = "Statistics Entities By Year";
	}
}


?>