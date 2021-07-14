require("DmsBase.php");

class <?= $tag ?> extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "<?= $tag ?>";
        $this->my_title = "<?= $title ?>";
    }
}

