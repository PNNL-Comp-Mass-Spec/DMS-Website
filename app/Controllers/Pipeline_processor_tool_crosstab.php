<?php
namespace App\Controllers;

class Pipeline_processor_tool_crosstab extends DmsBase {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "pipeline_processor_tool_crosstab";
        $this->my_title = "Step Tools";
    }
}
?>
