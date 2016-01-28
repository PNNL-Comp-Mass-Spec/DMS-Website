<?php
require("Base_controller.php");

// helper class
class Check_result {
	var $ok = true;
	var $message = "";
	var $path = '';
}

// main class
class File_attachment extends Base_controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
    	$this->load->helper(array('file_attachment','download'));
		$this->my_tag = "file_attachment";
		$this->my_title = "File Attachments";
		$this->archive_root_path = $this->config->item('file_attachment_archive_root_path');
	}
	// --------------------------------------------------------------------
	private
	function validate_remote_mount()
	{
		$result = new Check_result();
		try {
			$mnt_path = $this->archive_root_path;
			$result->path = $mnt_path;

			$dir_ok = is_dir($mnt_path);
			if(!$dir_ok) throw new Exception("'$mnt_path' is not a directory");

			$remote_sentinal = $mnt_path . "sentinel-remote.txt";
			$remote_ok = file_exists($remote_sentinal);
			if(!$remote_ok) throw new Exception("Remote sentinal '$remote_sentinal' was unexpectedly not visible");

			$local_sentinal = $mnt_path . "sentinel-local.txt";
			$local_ok = !file_exists($local_sentinal);
			if(!$local_ok) throw new Exception("Local sentinal '$local_sentinal' was unexpectedly visible");

		} catch (Exception $e) {
			$result->message = $e->getMessage();
			$result->ok = false;
		}
		if (ENVIRONMENT != 'production') $result->ok = true;
		return $result;
	}

	// --------------------------------------------------------------------
	function check_remote_mount()
	{
		var_dump($this->validate_remote_mount());
	}

	// --------------------------------------------------------------------
	function check_file_path($entity_type, $entity_id, $filename) {
		var_dump($this->get_valid_file_path($entity_type, $entity_id, $filename));
	}

	// --------------------------------------------------------------------
	function get_valid_file_path($entity_type, $entity_id, $filename){
		$result = new Check_result();
		try {
			$full_path = '';
			$this->load->database();
			$this->db->select("File_Name AS [filename], archive_folder_path as path");
			$this->db->where("Entity_Type", $entity_type);
			$this->db->where("Entity_ID", $entity_id);
			$this->db->where("File_Name", $filename);
			$query = $this->db->get("T_File_Attachment",1);

			if($query && $query->num_rows()>0) {
				$full_path = "{$this->archive_root_path}{$query->row()->path}/{$query->row()->filename}";
				$result->path = $full_path;
			} else {
				throw new Exception("Could not find entry for file in database");
			}
		} catch (Exception $e) {
			$result->message = $e->getMessage();
			$result->ok = false;
		}
		return $result;
	}

	// --------------------------------------------------------------------
	// copy uploaded file to receiving folder on web server,
	// make file attachment tracking entry in DMS,
	// and copy uploaded file to EMSL archive
	function upload()
	{
		$error =  "Upload was successful";
		try {
			$remote = $this->validate_remote_mount();
			if(!$remote->ok) throw new Exception($remote->message);

			$timestamp = microtime(TRUE);
			$config['upload_path'] = BASEPATH.'../attachment_uploads/'.$this->input->post("entity_id")."/{$timestamp}/";
			$config['allowed_types'] = '*';
			$config['max_width']  = '3000';
			$config['max_height']  = '3000';
			$config['overwrite'] = TRUE;
			$config['remove_spaces'] = TRUE;
			$config['encrypt_name'] = TRUE;
			$config['max_size'] = 204800;
			mkdir($config['upload_path'],0777,TRUE);

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload()) {
				$error = $this->upload->display_errors();
			} else {
				$data = $this->upload->data();
				$name = $data["file_name"];
				$size = $data["file_size"];
				$type = $this->input->post("entity_type");
				$id = $this->input->post("entity_id");
				$description = $this->input->post("description");
				$entity_folder_path = $this->get_path($type, $id);
				$archive_folder_path = $this->archive_root_path . $entity_folder_path;

				$dest_path = "{$archive_folder_path}/{$name}";
				$src_path = "{$config['upload_path']}{$name}";

				if(!file_exists($archive_folder_path)){
					mkdir($archive_folder_path,0777,true);
				}
				if(!rename($src_path,$dest_path)){
					//error occurred during copy, handle accordingly
				}else{
					rmdir(BASEPATH."../attachment_uploads/{$id}/{$timestamp}");
					chmodr($this->archive_root_path,0755);
				}

				$msg = $this->make_attachment_tracking_entry($name, $type, $id, $description, $size, $entity_folder_path);
				if($msg) throw new Exception($msg);
			}
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
		// output is headed for an iframe
		// this script will automatically run when put into it and will inform elements on main page that operation has completed
		echo "<script type='text/javascript'>parent.fileAttachment.report_upload_results('$error')</script>";
	}

	// --------------------------------------------------------------------
	// for testing get_path
	function path($entity_type, $entity_id) {
		echo $this->get_path($entity_type, $entity_id);
	}

	// --------------------------------------------------------------------
	// get storage path for attached files for the given entity
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

	// --------------------------------------------------------------------
	// make tracking entry in DMS database for attached file
	private
	function make_attachment_tracking_entry($name, $type, $id, $description, $size, $path)
	{
		$this->load->helper(array('user','url'));
		$response = "OK";
		try {
			// init sproc model
			$ok = $this->cu->load_mod('s_model', 'sproc_model', 'entry_sproc', $this->my_tag);
			if(!$ok) throw new exception($CI->sproc_model->get_error_text());

			$calling_params = new stdClass();

			$calling_params->ID = '0';
			$calling_params->FileName = $name;
			$calling_params->Description = $description;
			$calling_params->EntityType = $type;
			$calling_params->EntityID = $id;
			$calling_params->FileSizeBytes = $size;
			$calling_params->ArchiveFolderPath = $path;
			$calling_params->mode = 'add';
			$calling_params->callingUser = get_user();
			$calling_params->message = '';

			$ok = $this->sproc_model->execute_sproc($calling_params);
			if(!$ok) throw new exception($this->sproc_model->get_error_text());

			$ret = $this->sproc_model->get_parameters()->retval;
			$response = $this->sproc_model->get_parameters()->message;
		} catch (Exception $e) {
			$response = $e->getMessage();
		}
		return $response;
	}

	// --------------------------------------------------------------------
	// perform operation on given attached file
	// AJAX
	function perform_operation()
	{
		$mode = $this->input->post("mode");
		$id = $this->input->post("id");

		$this->load->helper(array('user','url'));
		$response = "OK";
		try {
			// init sproc model
			$ok = $this->cu->load_mod('s_model', 'sproc_model', 'operations_sproc', $this->my_tag);
			if(!$ok) throw new exception($CI->sproc_model->get_error_text());

			$calling_params = new stdClass();

			$calling_params->ID = $id;
			$calling_params->mode = $mode;
			$calling_params->callingUser = get_user();
			$calling_params->message = '';

			$ok = $this->sproc_model->execute_sproc($calling_params);
			if(!$ok) throw new exception($this->sproc_model->get_error_text());

			$ret = $this->sproc_model->get_parameters()->retval;
			$response = $this->sproc_model->get_parameters()->message;
		} catch (Exception $e) {
			$response = $e->getMessage();
		}
		return $response;
	}

	// --------------------------------------------------------------------
	// AJAX
	function show_attachments() {
		$type = $this->input->post("entity_type");
		$id = $this->input->post("entity_id");
    	$this->load->helper(array('link_util'));

		$this->load->database();
		$this->db->select("File_Name AS [Name], Description, ID as FID");
		$this->db->from("T_File_Attachment");
		$this->db->where("Entity_Type", $type);
		$this->db->where("Entity_ID", $id);
		$this->db->where("Active >", 0);
		$query = $this->db->get();
		if(!$query) return "Error querying database";
		$entries = array();
	    $icon_delete = table_link_icon('delete');
		$icon_download = table_link_icon('down');
	    foreach($query->result() as $row){
			$url = site_url() . "file_attachment/retrieve/{$type}/{$id}/{$row->Name}";
			$downloadLink = "<a href='javascript:void(0)' onclick=fileAttachment.doDownload('$url') title='Download this file'>$icon_download</span></a> ";
			$deleteLink = "<a href='javascript:void(0)' onclick=fileAttachment.doOperation('{$row->FID}','delete') title='Delete this file'>$icon_delete</span></a> ";
			$entries[] = array($downloadLink . ' ' . $deleteLink , $row->Name, $row->Description);
	    }
		$count = $query->num_rows();
		if($count) {
			$this->load->library('table');
	    	$this->table->set_heading("Action", "Name", "Description");
		    $tmpl = array(
		      'table_open'      => "<table id=\"file_attachments\" style=\"width:100%;\">",
		      'row_start'       => '<tr class="ReportEvenRow">',
		      'row_alt_start'   => '<tr class="ReportOddRow">',
		      'heading_row_start' => '<thead><tr style="text-align:left;">',
		      'heading_row_end' => '</tr></thead>'
		    );
	    	$this->table->set_template($tmpl);
			echo $this->table->generate($entries);
		} else {
			echo "<h4>No attachments</h4>";
		}
	}

	// --------------------------------------------------------------------
	function check_retrieve($entity_type, $entity_id, $filename)
	{
		$result = $this->validate_remote_mount();
		if($result->ok) {
	 		$result = $this->get_valid_file_path($entity_type, $entity_id, $filename);
		}
		if($result->ok) {
			if(!file_exists($result->path)) {
				$result->ok = false;
				$result->message = "File '$filename' could not be found on server";
			}
		}
		echo json_encode($result);
	}

	// --------------------------------------------------------------------
	function retrieve($entity_type, $entity_id, $filename){
		try {
			$remote = $this->validate_remote_mount();
			if(!$remote->ok) throw new Exception($remote->message);

	 		$result = $this->get_valid_file_path($entity_type, $entity_id, $filename);
		    if(!$result->ok) throw new Exception($result->message);
 			$full_path = $result->path;

			if(!file_exists($full_path)) throw new Exception('File could not be found on server');

			//get mimetype info
			$mime = mime_content_type($full_path);

			if(preg_match('/Opera ([0-9].[0-9]{1,2})/i', $_SERVER['HTTP_USER_AGENT']))
			$UserBrowser = "Opera";
			elseif (preg_match('/MSIE ([0-9].[0-9]{1,2})/i', $_SERVER['HTTP_USER_AGENT']))
			$UserBrowser = "IE";
			else
			$UserBrowser = '';

			/// important for download in most browsers
			$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ?
			'application/octetstream' : 'application/octet-stream';
			header("Content-type: {$mime}");
			header("Content-Disposition: attachment; filename=\"{$filename}\"");
			header("X-Sendfile: {$full_path}");

		} catch (Exception $e) {
			$error = $e->getMessage();
			echo $error;
		}
	}

	// --------------------------------------------------------------------
	// make file in EMSL archive using given contents,
	// make file attachment tracking entry in DMS,
	private
	function make_attached_file($name, $type, $id, $description, $contents)
	{
		$msg = "";
		$entity_folder_path = $this->get_path($type, $id);
		$archive_folder_path = $this->archive_root_path . $entity_folder_path;

		$dest_path = "{$archive_folder_path}/{$name}";

		try {
			if(!file_exists($archive_folder_path)){
				mkdir($archive_folder_path,0777,true);
			}
			$handle = fopen($dest_path, 'w+');
			if($handle === FALSE) {
				 throw new Exception("Could not open '$dest_path'");
			}
			if(fwrite($handle, $contents) === FALSE) {
				 throw new Exception("Could write to '$dest_path'");
			}
			fclose($handle);

			$size = number_format((filesize($dest_path) / 1024), 2, '.', '');

			$msg = $this->make_attachment_tracking_entry($name, $type, $id, $description, $size, $entity_folder_path);
		} catch (Exception $e) {
			$msg = $e->getMessage();
		}

		return $msg;
	}

	// --------------------------------------------------------------------
	function test() {
		$name = "auxinfo.txt";
		$type = "experiment";
		$id = "SWDev";
		$description = "Test direct";
		$contents = "How now, brown cow?";
		$msg = $this->make_attached_file($name, $type, $id, $description, $contents);
		echo $msg;
	}

	// --------------------------------------------------------------------
	function auxinfo($expID) {
		$this->load->database();

		$contents = "";

		$sql = "SELECT * FROM V_Experiment_Detail_Report_Ex WHERE ID = $expID";
		$query = $this->db->query($sql);
		if(!$query) return;
 		if ($query->num_rows() == 0) return;
		$result = $query->result_array();
		$fields = current($result);
		$id = $fields["Experiment"];
		$cols = array_keys($fields);
		foreach($cols as $col) {
			$contents .= $col . "\t" . $fields[$col] . "\n";
		}

		$sql = "SELECT Category, Subcategory, Item, Value FROM V_Auxinfo_Experiment_Values WHERE ID = $expID ORDER BY Category, Subcategory, Item";
		$query = $this->db->query($sql);
		if(!$query) return;
 		if ($query->num_rows() == 0) return;
		$result = $query->result_array();
		$fields = current($result);
		$cols = array_keys($fields);
		$contents .= "\n\n";
		$contents .= implode("\t", $cols) . "\n";
		foreach($result as $row) {
			$contents .= implode("\t", $row);
			$contents .= "\n";
		}

		$name = "auxinfo.txt";
		$type = "experiment";
		$description = "Automatically created auxinfo";
//		$msg = $this->make_attached_file($name, $type, $id, $description, $contents);
//		echo "$expID:$msg";
		header("Content-type: text/plain");
		echo $contents;
	}

	// --------------------------------------------------------------------
	function check_access(){
		try {
			$full_path = '';
			$this->load->database();
			$this->db->select("File_Name AS [filename], Entity_Type as type, Entity_ID as id, archive_folder_path as path");
			$this->db->where("Active > 0");
			$query = $this->db->get("T_File_Attachment");

			$this->load->library('table');
			$this->table->set_template(array ('heading_cell_start'  => '<th style="text-align:left;">'));

			$this->table->set_heading('File', 'Type', 'ID', 'Path');
			foreach($query->result() as $row) {
				$full_path = "{$this->archive_root_path}{$row->path}/{$row->filename}";
				if(!file_exists($full_path)) {
					$this->table->add_row($row->filename, $row->type, $row->id, $row->path);
				}
			}
			echo $this->table->generate();

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

}


?>