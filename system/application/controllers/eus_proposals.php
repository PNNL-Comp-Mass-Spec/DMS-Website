<?php
require("base_controller.php");

class eus_proposals extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "eus_proposals";
		$this->my_title = "EUS Proposals";
	}

}
?>