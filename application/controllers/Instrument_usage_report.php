<?php
require("Grid.php");

// Include the String operations methods
require_once(BASEPATH . '../application/libraries/String_operations.php');

class Instrument_usage_report extends Grid {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "instrument_usage_report";
		$this->my_title = "Instrument Usage";
	}

	// --------------------------------------------------------------------
	// --------------------------------------------------------------------
	function grid() {
//		$this->my_tag = "instrument_usage";
//		$this->my_title = "Instrument Usage Report";
		$save_url = 'instrument_usage_report/operation';
		$data_url = 'instrument_usage_report/grid_data';
		$this->grid_page('instrument_usage', $save_url, $data_url);
	}
	// --------------------------------------------------------------------
	function grid_data() {
		$instrument = $this->input->post("instrument");
		$usage = $this->input->post("usage");
		$proposal = $this->input->post("proposal");
		$year = $this->input->post("year");
		$month = $this->input->post("month");

		$this->my_tag = "instrument_usage";
		$this->load->database();
		$this->db->select('Seq , [EMSL Inst ID], Instrument , Type , CONVERT(VARCHAR(16), Start, 101) AS Start , Minutes , Proposal , Usage , Users , Operator , Comment , ID , Validation', FALSE);
		$this->db->from("V_Instrument_Usage_Report_List_Report");
                  
		if(IsNotWhitespace($instrument)) $this->db->where("Instrument in ($instrument)");
		if(IsNotWhitespace($usage)) $this->db->where("Usage in ($usage)");
		if(IsNotWhitespace($proposal)) $this->db->where("Proposal", $proposal);
		if(IsNotWhitespace($year)) $this->db->where("Year", $year);
		if(IsNotWhitespace($month)) $this->db->where("Month", $month);		
                
		$this->grid_data_from_query();
	}

	// --------------------------------------------------------------------
	function ws()
	{
		$year = $this->uri->segment(3, date(''));
		$month = $this->uri->segment(4, date(''));
		$instrument = $this->uri->segment(5, '');

		// Validate the month
		if(is_numeric($month)) {
			if((int)$month < 1) {
				$month = '1';
			} else {
				$month = (int)$month;
			}
		} else {
			$month = '1';
		}

		$result = $this->get_usage_data($instrument, $year, $month);
		$this->export_to_tab_delimited_text($result);
	}

	// --------------------------------------------------------------------
	private
	function get_usage_data($instrument, $year, $month)
	{
		$this->load->database();

		$sql = <<<EOD
SELECT *
FROM  V_Instrument_Usage_Report_Export
WHERE [Year] = $year AND [Month] = $month
ORDER BY [Instrument], [Year], [Month], [Start]
EOD;
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}

	// --------------------------------------------------------------------
	function daily()
	{
		$year = $this->uri->segment(3, date(''));
		$month = $this->uri->segment(4, date(''));
		$instrument = $this->uri->segment(5, '');

		// Validate the month
		if(is_numeric($month)) {
			if((int)$month < 1) {
				$month = '1';
			} else {
				$month = (int)$month;
			}
		} else {
			$month = '1';
		}

		$result = $this->get_daily_data($instrument, $year, $month);
		$this->export_to_tab_delimited_text($result);

	}

	// --------------------------------------------------------------------
	private
	function get_daily_data($instrument, $year, $month)
	{
		$this->load->database();

		$sql = "SELECT * FROM dbo.GetEMSLInstrumentUsageDaily($year, $month) WHERE NOT EMSL_Inst_ID Is Null";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}

	// --------------------------------------------------------------------
	function rollup()
	{
		$year = $this->uri->segment(3, date(''));
		$month = $this->uri->segment(4, date(''));
		$instrument = $this->uri->segment(5, '');

		// Validate the month
		if(is_numeric($month)) {
			if((int)$month < 1) {
				$month = '1';
			} else {
				$month = (int)$month;
			}
		} else {
			$month = '1';
		}

		$result = $this->get_rollup_data($instrument, $year, $month);
		$this->export_to_tab_delimited_text($result);

	}

	// --------------------------------------------------------------------
	private
	function get_rollup_data($instrument, $year, $month)
	{
		$this->load->database();

		$sql = "SELECT * FROM dbo.GetEMSLInstrumentUsageRollup($year, $month) WHERE NOT EMSL_Inst_ID Is Null";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}

	// --------------------------------------------------------------------
	//
	private
	function export_to_tab_delimited_text($result)
	{
		$headers = '';
		$data = '';

		$cols = array_keys(current($result));

		$headers = implode("\t", $cols);

		// field data
		foreach($result as $row) {
			$line = '';
			foreach($cols as $name) {
				$value = $row[$name];
				if (!isset($value) || $value == "") {
					 $value = "\t";
				}
				else {
					 $value .= "\t";
				}
				$line .= $value;
			}
			$data .= trim($line)."\n";
		}

		$data = str_replace("\r","",$data);

		header("Content-type: text/plain");
//		header("Content-Disposition: attachment; filename=$filename.txt");
		echo "$headers\n$data";
	}

}
?>