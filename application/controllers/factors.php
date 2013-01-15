<?php
require("base_controller.php");

class factors extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "factors";
		$this->my_title = "Factors";
	}

	// --------------------------------------------------------------------
	function grid() 
	{
		$data = array();
		$data['title'] = $this->my_title;
		$data['nav_bar_menu_items']= $this->get_basic_nav_bar_items();

		$this->load->vars($data);	
		$this->load->view('grid/grid_factors');				
	}

	// --------------------------------------------------------------------
	// get data from sproc
	function grid_data()
	{
		$this->cu->load_lib('grid_data', 'list_report_sproc', 'requested_run_factors');
		$response = $this->grid_data->get_grid_data($this->input->post());
		echo json_encode($response);
	}

}
?>