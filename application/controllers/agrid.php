<?php

class Agrid extends Controller {

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

}
?>