<?php
require("Base_controller.php");

class Experiment_file_attachment extends Base_controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
    	$this->load->helper(array('file_attachment','download'));
		$this->my_tag = "experiment_file_attachment";
		$this->my_title = "Experiment File Attachments";
		$this->archive_root_path = $this->config->item('file_attachment_archive_root_path');
	}

	/**
	 * For testing get_path; example usage:
	 * http://dms2.pnl.gov/experiment_file_attachment/path/experiment/150000
	 *  returns experiment/2015_2/150000
	 * http://dms2.pnl.gov/experiment_file_attachment/path/lc_cart_configuration/101
	 *  returns lc_cart_configuration/spread/101
	 * @param type $entity_type
	 * @param type $entity_id
	 */
	function path($entity_type, $entity_id) {
		echo $this->get_path($entity_type, $entity_id);
	}

	/**
	 * Get storage path for attached files for the given entity
	 * @param type $entity_type
	 * @param type $entity_id
	 * @return type
	 */
	private
	function get_path($entity_type, $entity_id)
	{
		$path = "";
		$sql = "SELECT dbo.GetFileAttachmentPath('$entity_type', '$entity_id') AS path";
		$this->load->database();
		$query = $this->db->query($sql);
		if(!$query) {
			$path = "Error querying database";
		} else {
			$result = $query->result();
			$path = $result[0]->path;
		}
		return $path;
	}

	/**
	 * Show attachments for an experiment
	 * This is likely unused in 2017
	 * @return string
	 * @category AJAX
	 */
	function show_attachments() {
		$type = $this->input->post("entity_type");
		$id = $this->input->post("entity_id");

		$this->load->database();
		$this->db->select("File_Name AS Name, Description");
		$this->db->from("T_File_Attachment");
		$this->db->where("Entity_Type", $type);
		$this->db->where("Entity_ID", $id);
		$this->db->where("Active >", "0");
		$query = $this->db->get();
		if(!$query) return "Error querying database";
		$entries = array();
	    foreach($query->result() as $row){
	      $path = "file_attachment/retrieve/{$type}/{$id}/{$row->Name}";
	      $entries[] = array(anchor($path,$row->Name),$row->Description);
	    }
		$count = $query->num_rows();
		$this->load->library('table');
    	$this->table->set_heading("Name","Description");

	    $tmpl = array(
	      'table_open'      => "<table class=\"DRep\" id=\"file_attachments\" style=\"width:100%;\">",
	      'row_start'       => '<tr class="ReportEvenRow">',
	      'row_alt_start'   => '<tr class="ReportOddRow">',
	      'heading_row_start' => '<thead><tr>',
	      'heading_row_end' => '</tr></thead>'
	    );

    	$this->table->set_template($tmpl);

		echo "<h2 style='text-align:center;'>Attachments ($count)</h2>";
		echo $this->table->generate($entries);
	}

	/**
	 * Retrieve the requested attachment
	 * @param type $entity_type
	 * @param type $entity_id
	 * @param type $filename
	 */
	function retrieve($entity_type,$entity_id,$filename){
//		  $this->output->enable_profiler(true);
	    $this->load->database();
	    $this->db->select("File_Name AS filename, archive_folder_path as path");
	    $this->db->where("Entity_Type", $entity_type);
	    $this->db->where("Entity_ID", $entity_id);
	    $this->db->where("File_Name", $filename);
	    $query = $this->db->get("T_File_Attachment",1);

	    if($query && $query->num_rows()>0){
			//build the full path
			$full_path = "{$this->archive_root_path}{$query->row()->path}/{$query->row()->filename}";
//      echo $full_path;
			//get mimetype info
			$mime = mime_content_type($full_path);

			if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
				$UserBrowser = "Opera";
			} elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
				$UserBrowser = "IE";
			} else {
				$UserBrowser = '';
			}

			/// important for download in most browsers
			$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ?
			'application/octetstream' : 'application/octet-stream';
			header("Content-type: {$mime}");
			header("Content-Disposition: attachment; filename=\"{$filename}\"");
			header("X-Sendfile: {$full_path}");
	    }
	}

}


?>