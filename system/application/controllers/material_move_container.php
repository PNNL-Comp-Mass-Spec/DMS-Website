
<?php
require("base_controller.php");

class material_move_container extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "material_move_container";
		$this->my_title = "Move Containers";
	}

}
?>