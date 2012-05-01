<?php
require("base_controller.php");

class eus_proposals_operation extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "eus_proposals_operation";		$this->my_model = "M_eus_proposals_operation";
		$this->my_title = "EUS Proposals Operation";
	}

}
?>