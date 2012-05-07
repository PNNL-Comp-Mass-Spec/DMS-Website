<?php
require("base_controller.php");

class helper_mrm_attachment extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_mrm_attachment";
		$this->my_title = "MRM Transition List Attachment Helper";
	}

}
?>