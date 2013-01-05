<?php
require("base_controller.php");

class Agrid extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		session_start();
		$this->load->helper(array('url', 'string'));
		$this->color_code = $this->config->item('version_color_code');
	}

	// --------------------------------------------------------------------
	function grid() 
	{
		$data['title'] = 'Grid Test';

		$this->load->vars($data);
		$this->load->view('grid/grid');

	}

	// --------------------------------------------------------------------
	function test_data()
	{
		$this->load->database();
		
		$instrument = ''; 
		$year = ''; 
		$month = ''; 
		
		$sql = <<<EOD
SELECT  
U_PRN AS PRN,
U_Name AS Name
FROM    T_Users
EOD;

		$outcome = new stdClass();
		$query = $this->db->query($sql);
		$outcome->Result = 'OK';
		$outcome->Message = '';
		$outcome->Records = $query->result();
		echo json_encode($outcome);
	}

	
	// --------------------------------------------------------------------
	function get_usage_data()
	{
		$this->load->database();
		
		$instrument = 'VOrbiETD01'; 
		$year = '2012'; 
		$month = '3'; 
	
		$sql = <<<EOD
SELECT Instrument, Type, Start, Minutes, Proposal, Usage, Users, Operator, Comment, Year, Month, ID, Seq 
FROM  T_EMSL_Instrument_Usage_Report
WHERE [Year] = $year AND [Month] = $month AND [Instrument] = '$instrument'
ORDER BY [Instrument], [Year], [Month], [Start]
EOD;
		$outcome = new stdClass();
		$query = $this->db->query($sql);
		$outcome->Result = 'OK';
		$outcome->Message = '';
		$outcome->Records = $query->result();
		echo json_encode($outcome);
	}

	// --------------------------------------------------------------------
	function factors() 
	{
		$data = array();
		$data['title'] = 'Factors Grid Test';
		$this->load->vars($data);	
		$this->load->view('grid/grid_factors');				
	}

	// --------------------------------------------------------------------
	// get data from sproc
	function factor_data()
	{
		$itemList = $this->input->post('itemList');
		$itemType = $this->input->post('itemType');
		
		$response = $this->get_factor_data($itemList, $itemType);
		echo json_encode($response);
	}
	// --------------------------------------------------------------------
	// get data from sproc
	private
	function get_factor_data($itemList, $itemType)
	{
		$this->load->helper(array('user','url'));
		$response = new stdClass();
		try {
			// init sproc model
			$ok = $this->cu->load_mod('s_model', 'sproc_model', 'list_report_sproc', 'requested_run_factors');
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