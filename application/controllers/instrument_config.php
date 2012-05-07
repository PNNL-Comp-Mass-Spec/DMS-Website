
<?php
require("base_controller.php");

class instrument_config extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "instrument_config";
		$this->my_title = "Instrument Configuration";
	}

}
?>