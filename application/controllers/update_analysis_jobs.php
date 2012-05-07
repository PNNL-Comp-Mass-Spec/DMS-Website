
<?php
require("base_controller.php");

class update_analysis_jobs extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "update_analysis_jobs";
		$this->my_title = "Update Analysis Jobs";
	}

}
?>