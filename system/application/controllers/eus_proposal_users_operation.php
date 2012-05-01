<?php
require("base_controller.php");

class eus_proposal_users_operation extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "eus_proposal_users_operation";
		$this->my_title = "EUS Proposal Users Operation";
	}

}
?>