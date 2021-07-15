<?php
namespace App\Controllers;

class Grid extends DmsBase {
    function __construct()
    {
        $this->my_tag = "";
        $this->my_title = "";
        $this->helpers = array_merge($this->helpers, ['link_util']);
    }

    // --------------------------------------------------------------------
    function index()
    {
        echo view("grid/demo");
    }
    // --------------------------------------------------------------------
    protected
    function grid_page($view_name, $save_url = '', $data_url = '')
    {
        // Include the String operations methods
        helper(['form', 'string']);
        $this->choosers = model('App\Models\Dms_chooser');
        $data = array();
        $data['title'] = $this->my_title;
        $data['nav_bar_menu_items']= $this->get_basic_nav_bar_items();

        // Example value for $data_url: "instrument_usage_report/grid_data"
        // That leads to $data['data_url'] = "https://dms2.pnl.gov/instrument_usage_report/grid_data"
        $data['data_url'] = ($data_url) ? site_url($data_url) : site_url("grid/" . $this->my_tag  . "_data");

        // Example value for $save_url: "instrument_usage_report/operation"
        // That leads to $data['save_url'] = "https://dms2.pnl.gov/instrument_usage_report/operation"
        $data['save_url'] = ($save_url) ? site_url($save_url) : site_url($this->my_tag  . "operation");

        echo view("grid/".$view_name, $data);
    }

    // --------------------------------------------------------------------
    // get data from sproc
    protected
    function grid_data_from_sproc($sproc_id, $config_db)
    {
        $this->load_lib('Grid_data', $sproc_id, $config_db);
        $response = $this->grid_data->get_sproc_data($this->input->post());
        echo json_encode($response);
    }

    // --------------------------------------------------------------------
    protected
    function grid_data_from_query($builder) {
        $response = new stdClass();
        try {
            $result = $builder->get();
            if(!$result) {
                $currentTimestamp = date("Y-m-d");
                throw new exception("Error querying database for grid data; see application/logs/log-$currentTimestamp.php");
            }
            $columns = array();
            foreach($result->field_data() as $field) {
                $columns[] = $field->name;
            }
            $response->result = 'ok';
            $response->message = '';
            $response->columns = $columns;
            $response->rows = $result->getResultArray();;
        } catch (Exception $e) {
            $response->result = 'error';
            $response->message = 'grid_data_from_query: ' . $e->getMessage();
        }
        echo json_encode($response);
    }

    // --------------------------------------------------------------------
    // --------------------------------------------------------------------
    function instrument_allocation() {
        $this->my_tag = "instrument_allocation";
        $this->my_title = "Instrument Allocation";
        $save_url = 'instrument_allocation/operation';
        $this->grid_page('instrument_allocation', $save_url);
    }
    // --------------------------------------------------------------------
    function instrument_allocation_data() {
        $this->my_tag = "instrument_allocation";
        $this->grid_data_from_sproc('instrument_allocation_data_sproc', 'grid');
    }

    // --------------------------------------------------------------------
    // --------------------------------------------------------------------
    function factors() {
        $this->my_tag = "factors";
        $this->my_title = "Factors";
        $save_url = 'requested_run_factors/operation';
        $this->grid_page('grid_factors', $save_url);
    }
    // --------------------------------------------------------------------
    function factors_data() {
        $this->my_tag = "factors";
        $this->grid_data_from_sproc('list_report_sproc', 'requested_run_factors');
    }
/*
    // --------------------------------------------------------------------
    // --------------------------------------------------------------------
    function requested_run() {
        $this->my_tag = "requested_run";
        $this->my_title = "Requested Run";
        $save_url = 'requested_run_batch_blocking/operation';
        $this->grid_page('requested_run_grid', $save_url);
    }
    // --------------------------------------------------------------------
    function requested_run_data() {
        $this->my_tag = "requested_run";
        $this->grid_data_from_sproc('requested_run_data_sproc', 'grid');
    }
*/

    // --------------------------------------------------------------------
    // --------------------------------------------------------------------
    function user() {
        $this->my_tag = "user";
        $this->my_title = "Users";
        $save_url = 'xxx/operation';
        $this->grid_page('user', $save_url);
    }
    // --------------------------------------------------------------------
    function user_data() {
        $this->my_tag = "user";
        $this->db = \Config\Database::connect();
        $builder = $this->db->table("T_Users");
        $builder->select('ID, U_PRN AS PRN, U_Name AS Name, U_HID AS HID, U_Status AS Status, U_Access_Lists AS Access, U_email AS Email, U_domain AS Domain, U_netid AS NetID, U_comment AS Comment, CONVERT(VARCHAR(12), U_created, 101) AS Created');
        $userName = $this->input->post("userName");
        if(IsNotWhitespace($userName)) {
            $builder->like('U_Name', $userName);
        }
        $allUsers = $this->input->post("allUsers");
        if($allUsers == 'false') {
            $builder->where('U_Status', 'Active');
        }
        $this->grid_data_from_query($builder);
    }
}
?>
