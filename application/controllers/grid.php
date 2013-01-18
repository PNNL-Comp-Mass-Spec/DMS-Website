<?php
require("base_controller.php");

class Grid extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "";
		$this->my_title = "";
	}

	// --------------------------------------------------------------------
	private
	function grid_page($view_name, $save_url = '', $data_url = '') 
	{
		$data = array();
		$data['title'] = $this->my_title;
		$data['nav_bar_menu_items']= $this->get_basic_nav_bar_items();
		$data['data_url'] = ($data_url) ? site_url() .  $data_url : site_url()  . "grid/" . $this->my_tag  . "_data";
		$data['save_url'] = ($save_url) ? site_url() .  $save_url : site_url()  . $this->my_tag  . "operation";;

		$this->load->vars($data);	
		$this->load->view("grid/".$view_name);		
	}

	// --------------------------------------------------------------------
	// get data from sproc
	private
	function grid_data_from_sproc($sproc_id, $config_db)
	{
		$this->cu->load_lib('grid_data', $sproc_id, $config_db);
		$response = $this->grid_data->get_sproc_data($this->input->post());
		echo json_encode($response);
	}

	// --------------------------------------------------------------------
	private
	function grid_data_from_query($sql) {
		$response = new stdClass();
		try {
			$this->load->database();
			$result = $this->db->query($sql);
			if(!$result) throw new exception('??');
			$columns = array();
			foreach($result->field_data() as $field) {
				$columns[] = $field->name;
			}
			$response->result = 'ok';
			$response->message = '';
			$response->columns = $columns;
			$response->rows = $result->result_array();;
		} catch (Exception $e) {
			$response->result = 'error';
			$response->message = $e->getMessage();			
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

	// --------------------------------------------------------------------
	// --------------------------------------------------------------------
	function requested_run() {
		$this->my_tag = "requested_run";
		$this->my_title = "Requested Run";
		$save_url = 'xxx/operation';
		$this->grid_page('requested_run_grid', $save_url);
	}
	// --------------------------------------------------------------------
	function requested_run_data() {
		$this->my_tag = "requested_run";
		$this->grid_data_from_sproc('requested_run_data_sproc', 'grid');
	}
	
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
		$sql = "SELECT * FROM T_Users";
		$this->grid_data_from_query($sql);
	}
		
}
?>