
<?php
require("base_controller.php");

class settings_files extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "settings_files";
		$this->my_title = "Settings File";
	}

}
?>