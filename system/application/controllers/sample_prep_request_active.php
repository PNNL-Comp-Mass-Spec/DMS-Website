
<?php
require("base_controller.php");

class sample_prep_request_active extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "sample_prep_request_active";
		$this->my_title = "Active Sample Prep Requests";
	}

}
?>