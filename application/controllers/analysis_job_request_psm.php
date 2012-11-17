<?php
require("base_controller.php");

class analysis_job_request_psm extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "analysis_job_request_psm";
		$this->my_title = "PSM Analysis Job Request";
	}
	

	// --------------------------------------------------------------------
	private
	function get_defaults_from_db() 
	{
		$this->cu->load_lib('operation', 'na', $this->my_tag);
		$response = $this->operation->internal_operation('operations_sproc');
		$response->parms = $this->operation->get_params();
		return $response;
	}
	
	// --------------------------------------------------------------------
	function get_defaults()
	{
		$results = $this->get_defaults_from_db();
			$metadata_tab = $this->make_metadata_table($results->parms->metadata);
			$supplemental_form = $this->make_supplemental_param_form($results->parms->defaults);
			$message = $this->make_message($results->message, $results->result);
			echo $metadata_tab . $supplemental_form . $message;
	}
	
	// --------------------------------------------------------------------
	private
	function make_message($message, $result)
	{
		$s = '';		
		$s .= "<div>$message</div>";
		if($result) {
			$s .= "<div>$result</div>";
		}
		return $s;
	}
	
	// --------------------------------------------------------------------
	private
	function make_metadata_table($metadata)
	{
		if(!$metadata) return "";
		$s = '';		
		$s .= "<table class='EPag'>";
		$md_list = explode('|', $metadata);
		$header = true;
		foreach($md_list as $md) {
			$kv = explode(':', $md);
			if(count($kv) == 3) {
				$k = $kv[0];
				$v = $kv[1];
				$c = $kv[2];
				if($header) {
					$s .= "<tr><th>$k</th><th>$v</th><th>$c</th></tr>\n";
					$header = false;					
				} else {
					$s .= "<tr><td>$k</td><td>$v</td><td>$c</td></tr>\n";
				}
			}
		}
		$s .= "</table>\n";
		return $s;
	}	


	// --------------------------------------------------------------------
	private
	function make_supplemental_param_form($default_values)
	{
		$dv_list = explode('|', $default_values);
		$dvs = array();
		foreach($dv_list as $dv) {
			$kv = explode(":", $dv);
			if(count($kv) == 2) {
				$dvs[$kv[0]] = $kv[1];
			}
		};
		
		$ToolName = $dvs['ToolName'];
		$toolfld = "<input type='hidden' name='suggested_ToolName' id='suggested_ToolName' size='100' value='$ToolName' />";

		$JobTypeName = $dvs['JobTypeName'];
		$jobTypefld = "<input type='hidden' name='suggested_JobTypeName' id='suggested_JobTypeName' size='100' value='$JobTypeName' />";
		
		$mods = array();
		if($dvs['DynMetOxEnabled'] == '1') $mods[] = 'DynMetOxEnabled';
		if($dvs['StatCysAlkEnabled'] == '1') $mods[] = 'StatCysAlkEnabled';
		if($dvs['DynSTYPhosEnabled'] == '1') $mods[] = 'DynSTYPhosEnabled';
		$Modifications = implode(',', $mods);
		$Modificationsfld = "<input type='hidden' name='suggested_mods' id='suggested_mods' size='100' value='$Modifications' />";
		
		$s = '';		
		$s .= "<form id='suggested_values'>";
		$s .= $jobTypefld."\n";
		$s .= $toolfld."\n";
		$s .= $Modificationsfld."\n";
		$s .= "</form>\n";
		
		return $s;
	}

/*
	// --------------------------------------------------------------------
	private
	function make_supplemental_param_form($default_values)
	{
		$dv_list = explode('|', $default_values);
		$dvs = array();
		foreach($dv_list as $dv) {
			$kv = explode(":", $dv);
			if(count($kv) == 2) {
				$dvs[$kv[0]] = $kv[1];
			}
		};
		
		$ToolName = $dvs['ToolName'];
		$toolfld = "<input type='text' name='ToolName' id='ToolName' size='100' value='$ToolName' />";

		$JobTypeName = $dvs['JobTypeName'];
		$jobTypefld = "<input type='text' name='JobTypeName' id='JobTypeName' size='100' value='$JobTypeName' />";
		
		$mods = array();
		if($dvs['DynMetOxEnabled'] == '1') $mods[] = 'DynMetOxEnabled';
		if($dvs['StatCysAlkEnabled'] == '1') $mods[] = 'StatCysAlkEnabled';
		if($dvs['DynSTYPhosEnabled'] == '1') $mods[] = 'DynSTYPhosEnabled';
		$Modifications = implode(',', $mods);
		$Modificationsfld = "<input type='text' name='xx' id='xx' size='100' value='$Modifications' />";
		
		$s = '';		
		$s .= "<table class='EPag'>";
		$s .= "<tr><th>Parameter</th><th>Value</th></tr>\n";
		$s .= "<tr><td>Job Type </td><td>$jobTypefld </td></tr>\n";
		$s .= "<tr><td>Search Tool</td><td>$toolfld </td></tr>\n";
		$s .= "<tr><td>Modifications to Consider</td><td>$Modificationsfld </td></tr>\n";
		$s .= "</table>\n";
		
		return $s;
	}
*/
	
}
?>