<?php
require("base_controller.php");

class archive_path extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "archive_path";
		$this->my_title = "Archive Path";
	}

}
?>