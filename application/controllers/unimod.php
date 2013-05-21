<?php
require("base_controller.php");

class unimod extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "unimod";
		$this->my_title = "Unimod Mods List Report";
	}
}


?>