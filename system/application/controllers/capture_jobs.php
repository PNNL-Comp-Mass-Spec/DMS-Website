
<?php
require("base_controller.php");

class capture_jobs extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "capture_jobs";
		$this->my_title = "Capture Jobs";
	}

}
?>