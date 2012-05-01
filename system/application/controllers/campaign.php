<?php
require("base_controller.php");

class campaign extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "campaign";
		$this->my_title = "Campaign";

	}

}
?>