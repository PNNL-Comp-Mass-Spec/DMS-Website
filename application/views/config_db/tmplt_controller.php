require("Base_controller.php");

class <?= $tag ?> extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "<?= $tag ?>";
		$this->my_title = "<?= $title ?>";
	}
}

