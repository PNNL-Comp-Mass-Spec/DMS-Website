
<?php
require("Base_controller.php");

class Dataset_redisposition extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "dataset_redisposition";
        $this->my_title = "Dataset Redisposition";
    }

}
?>
