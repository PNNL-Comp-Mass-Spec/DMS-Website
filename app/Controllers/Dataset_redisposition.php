<?php
namespace App\Controllers;

class Dataset_redisposition extends DmsBase {
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
