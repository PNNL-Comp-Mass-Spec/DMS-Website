
<?php
require("base_controller.php");

class pipeline_local_processors extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "pipeline_local_processors";
		$this->my_title = "Pipeline Local Processors";
	}

}
?>