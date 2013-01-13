<?php
require("base_controller.php");

class requested_run_grid extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "requested_run_grid";
		$this->my_title = "Requested Run";
	}
	// --------------------------------------------------------------------
	private
	function setup_basic_dms_page()
	{
		$this->load->helper(array('user', 'dms_search', 'menu'));
		$this->load->model('dms_menu', 'menu', TRUE);
		return get_nav_bar_menu_items('');
	}

	// --------------------------------------------------------------------
	function grid() 
	{
		$data = array();
		$data['title'] = 'Edit Requested Run Assignments';
		$data['nav_bar_menu_items']= $this->setup_basic_dms_page();

		$this->load->vars($data);	
		$this->load->view('grid/requested_run_grid');				
	}

	// --------------------------------------------------------------------
	// get data from sproc
	function grid_data()
	{
		$itemList = $this->input->post('itemList');
		$itemType = $this->input->post('itemType');
		
		$response = $this->get_grid_data($itemList, $itemType);
		echo json_encode($response);
	}
	// --------------------------------------------------------------------
	// get data from sproc
	private
	function get_grid_data($itemList, $itemType)
	{
		$this->load->helper(array('user','url'));
		$response = new stdClass();
		try {
			// init sproc model
			$ok = $this->cu->load_mod('s_model', 'sproc_model', 'list_report_sproc', 'requested_run_grid');
			if(!$ok) throw new exception($CI->sproc_model->get_error_text());
			
			$calling_params = new stdClass();			
			$calling_params->itemList = $itemList;
			$calling_params->itemType =  $itemType;
			$calling_params->infoOnly = '0';
			$calling_params->message = '';
			
			$ok = $this->sproc_model->execute_sproc($calling_params);
			if(!$ok) throw new exception($this->sproc_model->get_error_text());
	
			$response->result = 'ok';
			$response->message = $this->sproc_model->get_parameters()->message;	
			
			$response->columns = $this->sproc_model->get_col_names();
			$response->rows = $this->sproc_model->get_rows();
						
		} catch (Exception $e) {
			$response->result = 'error';
			$response->message = $e->getMessage();			
		}
		return $response;
	}

	// --------------------------------------------------------------------
	private
	function make_col_specs($colNames) 
	{
		$colSpec = array();
		
		foreach($colNames as $colName) {
			$spec = new stdClass();
			$colSpec[] = $spec;
		}
		
		return colSpec;
	}

}
?>