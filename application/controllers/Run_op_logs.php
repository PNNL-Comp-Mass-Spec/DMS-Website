<?php
require("Grid.php");

// Include the String operations methods
require_once(BASEPATH . '../application/libraries/String_operations.php');

class Run_op_logs extends Grid {

    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "run_op_logs";
        $this->my_title = "Operation Logs";
    }

    // --------------------------------------------------------------------
    // Overrides index() in Grid.php
    function index()
    {
        // Don't show the "Editing Grid Demonstration Pages".
        // Redirect to the appropriate grid editing page
        redirect($this->my_tag.'/grid');
    }

    // --------------------------------------------------------------------
    // display grid editing page
    // --------------------------------------------------------------------
    function grid() {
        $this->my_tag = "operation_log_review";
        $this->my_title = "Operation Log Review";
        // The target for update_sproc is defined in Model Config DB run_op_logs and defaults to UpdateRunOpLog
        $save_url = 'run_op_logs/call/update_sproc';
        $data_url = 'run_op_logs/grid_data';
        $this->grid_page('operation_log_review', $save_url, $data_url);
    }
    // --------------------------------------------------------------------
    // get data for grid editing page (JSON)
    // --------------------------------------------------------------------
        function grid_data() {
        $instrument = $this->input->post("instrument");
        $usage = $this->input->post("usage");
        $type = $this->input->post("type");
        $year = $this->input->post("year");
        $month = $this->input->post("month");

        $this->my_tag = "operation_log_review";
        $this->load->database();
        $this->db->select("CONVERT(VARCHAR(16), Entered, 101) AS Entered, EnteredBy, Instrument, Type, Minutes, ID, Log, Request, Usage, Proposal, EMSL_User, Note");
        $this->db->from("V_Ops_Logs_List_Report");
        if(IsNotWhitespace($instrument)) $this->db->where("Instrument in ($instrument)");
        if(IsNotWhitespace($usage)) $this->db->where("Usage in ($usage)");
        if(IsNotWhitespace($type)) $this->db->where("Type in ($type)");
        if(IsNotWhitespace($year)) $this->db->where("Year", $year);
        if(IsNotWhitespace($month)) $this->db->where("Month", $month);
        $this->grid_data_from_query();
    }

}
?>
