
<?php
require("Base_controller.php");

class New_instrument extends Base_controller {


    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "new_instrument";
        $this->my_title = "Add New Instrument";
    }

}
?>
