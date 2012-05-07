<?php
require("base_controller.php");

class instrument_usage_report extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "instrument_usage_report";
		$this->my_title = "Instrument Usage";

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
FROM  T_EMSL_Instrument_Usage_Report
WHERE [Year] = $year AND [Month] = $month
ORDER BY [Instrument], [Year], [Month], [Start]
EOD;
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
				if ((!isset($value)) OR ($value == "")) {
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