<?php
require("base_controller.php");

class instrument_allocation extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "instrument_allocation";
		$this->my_title = "Instrument Allocation";
	}

	// --------------------------------------------------------------------
	function grid() 
	{
		$data = array();
		$data['title'] = $this->my_title;
		$data['nav_bar_menu_items']= $this->get_basic_nav_bar_items();

		$this->load->vars($data);	
		$this->load->view('grid/instrument_allocation');				
	}

	// --------------------------------------------------------------------
	// get data from sproc
	function grid_data()
	{
		$this->cu->load_lib('grid_data', 'instrument_allocation_data_sproc', 'grid');
		$response = $this->grid_data->get_grid_data($this->input->post());
		echo json_encode($response);
	}

}
?>