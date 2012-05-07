<?php
require("base_controller.php");

class data_package extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "data_package";
		$this->my_title = "DMS Data Package";
		$this->my_create_action = "enter";
		$this->my_edit_action = "enter";
		$this->my_operation_action = "operation";
		$this->my_list_action = "unrestricted";
		$this->my_export_action = "unrestricted";
		$this->my_show_action = "unrestricted";

		// This causes a link to the detail report page to appear on entry page following successful submit
//		$this->my_post_submission_detail_id = "ID";
	}

	// --------------------------------------------------------------------
	// (someday) use q_model
	function ag($id, $tool, $mode) {
		$this->load->helper(array('url', 'string'));

		$this->load->database('package');

		$sql = "dbo.CheckDataPackageDatasetJobCoverage($id, '$tool', '$mode')";
		$this->db->from($sql);
		$query = $this->db->get();
		if(!$query) return "Error querying database";
 		if ($query->num_rows() == 0) return "No rows found";
		$result = $query->result_array();
		$fields = $query->list_fields();

	    header("Content-type: text/plain");
		echo "-- $mode Package:$id Tool:$tool --\n";
		foreach($result as $row) {
			echo $row['Dataset']."\n";
		}
	}
	
	// --------------------------------------------------------------------
	function metadata($id) {
		$this->load->helper(array('url', 'string'));

		$this->load->database('package');
		$sqlList = array(
			"EMSL_Proposals" => "SELECT DISTINCT [EMSL Proposal] FROM V_Data_Package_Datasets_List_Report WHERE NOT [EMSL Proposal] IS NULL AND ID = $id",
			"Package" => "SELECT * FROM V_Data_Package_Detail_Report WHERE ID = $id",
			"Jobs" => "SELECT * FROM V_Data_Package_Analysis_Jobs_List_Report WHERE ID = $id",
			"Datasets" => "SELECT * FROM V_Data_Package_Datasets_List_Report WHERE ID = $id",
			"Experiments" => "SELECT * FROM V_Data_Package_Experiments_List_Report WHERE ID = $id",
			"Biomaterial" => "SELECT * FROM V_Data_Package_Biomaterial_List_Report WHERE ID = $id",
		);
		
		$ignoreColumns = array('ID', 'Dataset Folder Path', 'Archive Folder Path', 'Share Path', 'Web Path', 'PRISM Wiki');
		
		header("Content-type: text/plain");

		echo "<?xml version='1.0' encoding='utf-8'?>\n";
		echo "<metadata>\n";
		echo "<DMS ID='$id' />\n";
		foreach($sqlList as $section => $sql) {
			echo "<$section>\n";
			$query = $this->db->query($sql);
			if(!$query) continue;
	 		if ($query->num_rows() == 0) continue;
			$result = $query->result_array();
			$cols = array_keys(current($result));
			foreach($result as $row) {
				echo "<item ";
				foreach($cols as $name) {
					if(in_array($name,$ignoreColumns)) continue;
					$value = ($row[$name])?$row[$name]:"";
					$name = str_replace(" ","_", $name);
					$value = htmlspecialchars($value);
					echo "$name='$value' ";
				}
				echo " />\n";
			}
			echo "</$section>\n";
		}	
		echo "</metadata>\n";
	}
	
}
?>
