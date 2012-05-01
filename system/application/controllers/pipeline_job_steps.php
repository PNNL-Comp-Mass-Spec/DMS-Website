
<?php
require("base_controller.php");

class pipeline_job_steps extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "pipeline_job_steps";
		$this->my_title = "Pipeline Job Steps";
	}

}
?>