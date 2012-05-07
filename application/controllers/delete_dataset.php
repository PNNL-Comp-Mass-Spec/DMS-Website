
<?php
require("base_controller.php");

class delete_dataset extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "delete_dataset";
		$this->my_title = "Delete Dataset";
	}

}
?>