
<?php
require("base_controller.php");

class capture_step_tools extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "capture_step_tools";
		$this->my_title = "Step Tools";
	}

}
?>