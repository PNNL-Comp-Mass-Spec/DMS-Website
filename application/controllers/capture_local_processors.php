
<?php
require("base_controller.php");

class capture_local_processors extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "capture_local_processors";
		$this->my_title = "Capture Local Processors";
	}

}
?>