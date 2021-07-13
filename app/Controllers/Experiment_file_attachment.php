<?php
require("File_attachment.php");

/**
 * This class is only needed for the /experiment_file_attachment report page
 * all other needed functionality is in File_attachment
 */
class Experiment_file_attachment extends File_attachment {

    /**
     * Constructor
     */
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();
        $this->my_tag = "experiment_file_attachment";
        $this->my_title = "Experiment File Attachments";
    }

    /**
     * For testing get_path; example usage:
     * http://dms2.pnl.gov/experiment_file_attachment/path/experiment/150000
     *  returns experiment/2015_2/150000
     * http://dms2.pnl.gov/experiment_file_attachment/path/lc_cart_configuration/101
     *  returns lc_cart_configuration/spread/101
     */
}


?>
