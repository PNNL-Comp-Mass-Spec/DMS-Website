
<?php
require("Base_controller.php");

class Capture_local_processors extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "capture_local_processors";
        $this->my_title = "Capture Local Processors";
    }

}
?>
