<?php
require("base_controller.php");

class mrm_list_attachment extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "mrm_list_attachment";
		$this->my_title = "MRM Transition List Attachment";
		$this->my_create_action = "enter";
		$this->my_edit_action = "enter";
		$this->my_list_action = "unrestricted";
		$this->my_export_action = "unrestricted";
	}

	// --------------------------------------------------------------------
	function download($id)
	{
		$sql = "SELECT File_Name, Attachment_Name, Contents FROM T_Attachments WHERE ID = $id";
		$this->load->database();
		$result = $this->db->query($sql);
		//
		if(!$result) {
			echo "No results found";
			return;
		}
		$file_info = $result->row();
		$filename = str_replace(" ", "_", $file_info->Attachment_Name) . ".txt";

		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=$filename");
		echo $file_info->Contents;
	}


}
?>