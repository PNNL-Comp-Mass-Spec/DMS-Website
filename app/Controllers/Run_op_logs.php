<?php
namespace App\Controllers;

class Run_op_logs extends Grid {
    function __construct()
    {
        // Call the parent (Grid) constructor
        parent::__construct();

        $this->my_tag = "run_op_logs";
        $this->my_title = "Operation Logs";

        // Include the String operations methods
        $this->helpers = array_merge($this->helpers, ['string']);
    }

    // --------------------------------------------------------------------
    // Overrides index() in Grid.php
    // --------------------------------------------------------------------
    function index()
    {
        // Don't show the "Editing Grid Demonstration Pages".
        // Redirect to the appropriate grid editing page
        return redirect()->to(site_url($this->my_tag.'/grid'));
    }

    // --------------------------------------------------------------------
    // Grid editing page:
    // https://dms2.pnl.gov/run_op_logs/grid
    // --------------------------------------------------------------------
    function grid() {
        $this->my_tag = "operation_log_review";
        $this->my_title = "Operation Log Review";
        // The target for update_sproc is defined in Model Config DB run_op_logs and defaults to update_run_op_log
        $save_url = 'run_op_logs/call/update_sproc';
        $data_url = 'run_op_logs/grid_data';
        $this->grid_page('operation_log_review', $save_url, $data_url);
    }

    // --------------------------------------------------------------------
    // get data for grid editing page (JSON)
    // --------------------------------------------------------------------
    function grid_data() {
        $instrument = $this->request->getPost("instrument");
        $usage = $this->request->getPost("usage");
        $type = $this->request->getPost("type");
        $year = $this->request->getPost("year");
        $month = $this->request->getPost("month");

        $this->my_tag = "operation_log_review";
        $this->db = \Config\Database::connect();
        $this->updateSearchPath($this->db);
        $builder = $this->db->table("v_ops_logs_list_report");
        $builder->select("entered, entered_by, instrument, type, minutes, id, log, request, usage, proposal, emsl_user, note");
        if(IsNotWhitespace($instrument)) $builder->where("instrument in ($instrument)");
        if(IsNotWhitespace($usage)) $builder->where("usage in ($usage)");
        if(IsNotWhitespace($type)) $builder->where("type in ($type)");
        if(IsNotWhitespace($year)) $builder->where("year", $year);
        if(IsNotWhitespace($month)) $builder->where("month", $month);
        $this->grid_data_from_query($builder);
    }
}
?>
