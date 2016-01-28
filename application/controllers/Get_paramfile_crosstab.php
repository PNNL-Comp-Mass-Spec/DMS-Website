<?php
require("Base_controller.php");

class Get_paramfile_crosstab extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "get_paramfile_crosstab";
		$this->my_title = "Paramfile Crosstab";
	}

}
?>