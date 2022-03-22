namespace App\Controllers;

class <?= ucfirst($tag) ?> extends DmsBase {
    function __construct()
    {
        $this->my_tag = "<?= $tag ?>";
        $this->my_title = "<?= $title ?>";
    }
}