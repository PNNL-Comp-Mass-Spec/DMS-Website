
<?php
require("base_controller.php");

class instrument_config_history extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "instrument_config_history";
		$this->my_title = "Instrument Configuration History";
	}

}
?>