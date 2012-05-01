
<?php
require("base_controller.php");

class material_container extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "material_container";
		$this->my_title = "Material Container";

	}

}
?>