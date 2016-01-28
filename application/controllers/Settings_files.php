
<?php
require("Base_controller.php");

class Settings_files extends Base_controller {


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