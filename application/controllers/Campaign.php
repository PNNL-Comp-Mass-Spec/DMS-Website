<?php
require("Base_controller.php");

class Campaign extends Base_controller {

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