<?php
require("Base_controller.php");

/**
 * Helper class
 */
class Check_result {
	var $ok = true;
	var $message = "";
	var $path = '';
}

/**
 * File attachment uploader class
 */
class File_attachment extends Base_controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
    	$this->load->helper(array('file_attachment','download'));
		$this->my_tag = "file_attachment";
		$this->my_title = "File Attachments";
		$this->archive_root_path = $this->config->item('file_attachment_archive_root_path');
	}
	
	/**
	 * Validate the availability of the remote mount
	 * @return Check_result
	 * @throws Exception
	 */
	private
	function validate_remote_mount()
	{
		$result = new Check_result();
		try {
			// $this->archive_root_path is typically /mnt/dms_attachments
			// Note that on Prismweb2 this path is only accessible via the apache user; commands:
			//   sudo su -
			//   su apache
			//   ls /mnt/dms_attachments
			// Alternatively, use /mnt/dmsarch-ro/dms_attachments
			$mnt_path = $this->archive_root_path;
			$result->path = $mnt_path;

			$dir_ok = is_dir($mnt_path);
			if (!$dir_ok) {
				throw new Exception("'$mnt_path' is not a directory");
			}

			$remote_sentinal = $mnt_path . "sentinel-remote.txt";
			$remote_ok = file_exists($remote_sentinal);
			if (!$remote_ok) {
				throw new Exception("Remote sentinal '$remote_sentinal' was unexpectedly not visible");
			}

			$local_sentinal = $mnt_path . "sentinel-local.txt";
			$local_ok = !file_exists($local_sentinal);
			if (!$local_ok) {
				throw new Exception("Local sentinal '$local_sentinal' was unexpectedly visible");
			}
		} catch (Exception $e) {
			$result->message = $e->getMessage();
			$result->ok = false;
		}
		
		if (ENVIRONMENT != 'production') {
			$result->ok = true;
		}
		return $result;
	}

	/**
	 * View the status of the remote mount
	 * http://dms2.pnl.gov/file_attachment/check_remote_mount
	 */
	function check_remote_mount()
	{
		$this->var_dump_ex($this->validate_remote_mount());
	}

	/**
	 * View the status of a file
	 * http://dms2.pnl.gov/file_attachment/check_file_path/lc_cart_configuration/100/TestFile.txt
	 * @param type $entity_type
	 * @param type $entity_id
	 * @param type $filename
	 */
	function check_file_path($entity_type, $entity_id, $filename) {
		$this->var_dump_ex($this->get_valid_file_path($entity_type, $entity_id, $filename));
	}

	/**
	 * Get the file path for the given item by querying T_File_Attachment
	 * @param type $entity_type
	 * @param type $entity_id
	 * @param type $filename
	 * @return \Check_result
	 * @throws Exception
	 */
	function get_valid_file_path($entity_type, $entity_id, $filename){
		$result = new Check_result();
		try {
			$full_path = '';
			$this->load->database();
			$this->db->select("File_Name AS [filename], archive_folder_path as path");
			$this->db->from("T_File_Attachment");
			$this->db->where("Entity_Type", $entity_type);
			$this->db->where("Entity_ID", $entity_id);
			$this->db->where("File_Name", $filename);
			$sql = $this->db->get_compiled_select();
			$query = $this->db->query($sql);
			//$query = $this->db->get();
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

	/**
	 * Copy uploaded file to receiving folder on web server,
	 * make file attachment tracking entry in DMS,
	 * and copy uploaded file to EMSL archive
	 * @throws Exception
	 */
	function upload()
	{
		$resultMsg =  "Upload was successful";
		try {
			$remote = $this->validate_remote_mount();
			if (!$remote->ok) {
				throw new Exception($remote->message);
			}

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
			
			// Upload the file from the user's computer to this server
			// Store below BASEPATH/../attachment_uploads 
			if ( ! $this->upload->do_upload()) {
				$resultMsg = $this->upload->display_errors();
			} else {
				$data = $this->upload->data();		
				$orig_name = $data["orig_name"];
				$name = $data["file_name"];
				$size = $data["file_size"];
				$type = $this->input->post("entity_type");
				$id = $this->input->post("entity_id");
				$description = $this->input->post("description");
				$entity_folder_path = $this->get_path($type, $id);
				$archive_folder_path = $this->archive_root_path . $entity_folder_path;

				$dest_path = "{$archive_folder_path}/{$orig_name}";
				$src_path = "{$config['upload_path']}{$name}";

				if(!file_exists($archive_folder_path)) {
					mkdir($archive_folder_path,0777,true);
				}
				
				$sourceFileSize = filesize($src_path);
				
				// Old method: rename($src_path, $dest_path)
				// Leads to warnings like this: 
				//   Warning --> rename(.../attachment_uploads/...,/mnt/dms_attachments/...): Operation not permitted
				// The solution is to use a copy then an unlink
				
				if(!copy($src_path, $dest_path)) {
					// Error occurred during copy, raise an exception
					throw new Exception("Could not copy '$src_path' to '$dest_path'");
				} else {
					// Copy succeeded
					// Confirm that the file size of the remote file matches the source file size
					
					$destFileSize = filesize($dest_path);
					
					if (!$sourceFileSize == $destFileSize) {
						// File sizes to not match
						throw new Exception("Length of the archived file ($destFileSize) "
							. "does not match the source file ($sourceFileSize): '$src_path' to '$dest_path'");
					}
					
					// If the file is less than 100 MB in size, compute sha1 checksums and compare
					if ($sourceFileSize < 100*1024*1024){
						$sourceSHA1 = sha1_file($src_path);
						if ($sourceSHA1 === false) {
							// Checksumming failed
							// Log an error, but move on
							log_message('error', "sha1_file returned false for $src_path");
						} else {
							$destSHA1 = sha1_file($dest_path);
							if ($destSHA1 === false) {
								// Checksumming failed
								// Log an error, but move on
								log_message('error', "sha1_file returned false for $destSHA1");
							} else {
								if (strcmp($sourceSHA1, $destSHA1) !== 0) {
									throw new Exception("Checksums do not match for file attachment: "
										. "'$src_path' and '$dest_path' have $sourceSHA1 and $destSHA1");
								}
							}							
						}																		
					}
					
					// Delete the local file
					unlink($src_path);
					
					rmdir(BASEPATH."../attachment_uploads/{$id}/{$timestamp}");
					chmodr($this->archive_root_path,0755);
				}

				$msg = $this->make_attachment_tracking_entry($orig_name, $type, $id, $description, $size, $entity_folder_path);
				if ($msg) {
					throw new Exception($msg);
				}
			}
		} catch (Exception $e) {
			$resultMsg = $e->getMessage();
		}
		// output is headed for an iframe
		// this script will automatically run when put into it and will inform elements on main page that operation has completed
		echo "<script type='text/javascript'>parent.fileAttachment.report_upload_results('$resultMsg')</script>";
	}

	/**
	 * For testing get_path; example usage:
	 * http://dms2.pnl.gov/file_attachment/path/dataset/255000
	 *  returns dataset/2012_1/255000
	 * http://dms2.pnl.gov/file_attachment/path/lc_cart_configuration/100
	 *  lc_cart_configuration/spread/100
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
	 * Make tracking entry in DMS database for attached file
	 * @param type $name
	 * @param type $type
	 * @param type $id
	 * @param type $description
	 * @param type $size
	 * @param type $path
	 * @return type
	 * @throws exception
	 */
	private
	function make_attachment_tracking_entry($name, $type, $id, $description, $size, $path)
	{
		$this->load->helper(array('user','url'));
		$response = "OK";
		try {
			// init sproc model
			$ok = $this->cu->load_mod('s_model', 'sproc_model', 'entry_sproc', $this->my_tag);
			if (!$ok) {
				throw new exception($CI->sproc_model->get_error_text());
			}

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
			if (!$ok) {
				throw new exception($this->sproc_model->get_error_text());
			}

			$ret = $this->sproc_model->get_parameters()->retval;
			$response = $this->sproc_model->get_parameters()->message;
		} catch (Exception $e) {
			$response = $e->getMessage();
		}
		return $response;
	}

	/**
	 * Perform operation on given attached file
	 * @return type
	 * @throws exception
	 * @category AJAX
	 */
	function perform_operation()
	{
		$mode = $this->input->post("mode");
		$id = $this->input->post("id");

		$this->load->helper(array('user','url'));
		$response = "OK";
		try {
			// init sproc model
			$ok = $this->cu->load_mod('s_model', 'sproc_model', 'operations_sproc', $this->my_tag);
			if (!$ok) {
				throw new exception($CI->sproc_model->get_error_text());
			}

			$calling_params = new stdClass();

			$calling_params->ID = $id;
			$calling_params->mode = $mode;
			$calling_params->callingUser = get_user();
			$calling_params->message = '';

			$ok = $this->sproc_model->execute_sproc($calling_params);
			if (!$ok) {
				throw new exception($this->sproc_model->get_error_text());
			}

			$ret = $this->sproc_model->get_parameters()->retval;
			$response = $this->sproc_model->get_parameters()->message;
		} catch (Exception $e) {
			$response = $e->getMessage();
		}
		return $response;
	}

	/**
	 * Show attachments for this entity
	 * This is likely unused in 2017
	 * @return string
	 * @category AJAX
	 */
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
		if (!$query) {
			return "Error querying database";
		}
		
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

	/**
	 * Confirm that the attachment can be retrieved
	 * http://dms2.pnl.gov/file_attachment/check_retrieve/experiment/SWDev/MageMerge.docx
	 * @param type $entity_type
	 * @param type $entity_id
	 * @param type $filename
	 * @returns string Json encoded string
	 */
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
		
		// Example JSON returned:
		// {"ok":false,"message":"Could not find entry for file in database","path":""}
		// {"ok":true,"message":"","path":"\/mnt\/dms_attachments\/experiment\/2000_5\/87\/MageMerge.docx"}
		echo json_encode($result);
	}

	/**
	 * Retrieve the attachment for the given entity
	 * @param type $entity_type
	 * @param type $entity_id
	 * @param type $filename
	 * @throws Exception
	 */
	function retrieve($entity_type, $entity_id, $filename){
		try {
			$remote = $this->validate_remote_mount();
			if (!$remote->ok) {
				throw new Exception($remote->message);
			}

			$result = $this->get_valid_file_path($entity_type, $entity_id, $filename);

			//echo "-->" . $result ."<--";
	 		
		    if (!$result->ok) {
				throw new Exception($result->message);
			}
			$full_path = $result->path;

			if (!file_exists($full_path)) {
				throw new Exception('File could not be found on server');
			}

			//get mimetype info
			$mime = mime_content_type($full_path);

			if (preg_match('/Opera ([0-9].[0-9]{1,2})/i', $_SERVER['HTTP_USER_AGENT'])) {
				$UserBrowser = "Opera";
			} elseif (preg_match('/MSIE ([0-9].[0-9]{1,2})/i', $_SERVER['HTTP_USER_AGENT'])) {
				$UserBrowser = "IE";
			} else {
				$UserBrowser = '';
			}

			// Old code:
			// important for download in most browsers
			// $mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ?
			// 'application/octetstream' : 'application/octet-stream';
			header("Content-type: {$mime}");
			header("Content-Disposition: attachment; filename=\"{$filename}\"");
			header("X-Sendfile: {$full_path}");

		} catch (Exception $e) {
			$error = $e->getMessage();
			echo $error;
		}
	}

	/**
	 * Make file in EMSL archive using given contents,
	 * then make file attachment tracking entry in DMS.
	 * @param type $name
	 * @param type $type
	 * @param type $id
	 * @param type $description
	 * @param type $contents
	 * @return type
	 * @throws Exception
	 */
	private
	function make_attached_file($name, $type, $id, $description, $contents)
	{
		$msg = "";
		$entity_folder_path = $this->get_path($type, $id);
		$archive_folder_path = $this->archive_root_path . $entity_folder_path;

		if (strcasecmp($id, "SWDev") == 0) {
			echo "<table border='1'>\n";
			echo "<tr><td>Experiment</td><td>$id</td></tr>\n";
			echo "<tr><td>Target folder</td><td>$entity_folder_path</td></tr>\n";
			echo "<tr><td>Full path</td><td>$archive_folder_path</td></tr>\n";
			echo "</table>\n";
		}
		
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
			
			if (strcasecmp($id, "SWDev") == 0 && strlen($msg) == 0) {
				$msg = "<br>Created file $dest_path <br>and called make_attachment_tracking_entry";
			}
			
		} catch (Exception $e) {
			$msg = $e->getMessage();
		}

		return $msg;
	}

	/**
	 * Test creating attachment named auxinfo.txt for experiment SWDev
	 * http://dmsdev.pnl.gov/file_attachment/test
	 */
	function test() {
		$name = "auxinfo.txt";
		$type = "experiment";
		$id = "SWDev";
		$description = "Test direct";
		$contents = "How now, brown cow?\n";
		$msg = $this->make_attached_file($name, $type, $id, $description, $contents);
		echo $msg;
	}

	/**
	 * Retrieve data from V_Experiment_Detail_Report_Ex and V_Auxinfo_Experiment_Values for Experiment $expID
	 * @param type $expID
	 * @return type
	 */
	function auxinfo($expID) {
		$this->load->database();

		$contents = $this->getExperimentInfo($expID);		
		if (empty($contents)) {
			return;
		}

		$sql = "SELECT Category, Subcategory, Item, Value "
			 . "FROM V_Auxinfo_Experiment_Values "
			 . "WHERE ID = $expID "
			 . "ORDER BY Category, Subcategory, Item";
		
		$query = $this->db->query($sql);
		if (!$query) {
			return;
		}
		if ($query->num_rows() == 0) {
			return;
		}
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

	/**
	 * Retrieve data from V_Experiment_Detail_Report_Ex for Experiment $expID
	 * @param type $expID
	 * @return string
	 */
	function getExperimentInfo($expID)
	{
		$sql = "SELECT * FROM V_Experiment_Detail_Report_Ex WHERE ID = $expID";
		$query = $this->db->query($sql);
		if (!$query) {
			return;
		}
		if ($query->num_rows() == 0) {
			return;
		}
		
		$result = $query->result_array();
		$fields = current($result);
		$id = $fields["Experiment"];
		$cols = array_keys($fields);
		foreach($cols as $col) {
			$contents .= $col . "\t" . $fields[$col] . "\n";
		}
		
		return $contents;
	}
	
	/**
	 * Verify that known attachments exist
	 * By default only shows missing files
	 * Append "show_all_files" to see all files
	 * Append any other text to only show files that contain that text in the name
	 *
	 * Example URLs:
	 * http://dms2.pnl.gov/file_attachment/check_access
	 * http://dms2.pnl.gov/file_attachment/check_access/show_all_files	 
	 * http://dms2.pnl.gov/file_attachment/check_access/txt
	 */
	function check_access(){
		try {
			$filterOption = $this->uri->segment(3, "");
			$filenameFilter = "";
			
			if (strlen($filterOption) == 0)
				$showAll = false;
			else {
				if (strtolower($filterOption) == "show_all_files")
					$filenameFilter = "";
				else
					$filenameFilter = strtolower($filterOption);

				$showAll = true;
			}
			
			$full_path = '';
			$this->load->database();
			$this->db->select("File_Name AS [filename], Entity_Type as type, Entity_ID as id, archive_folder_path as path");
			$this->db->where("Active > 0");
			$query = $this->db->get("T_File_Attachment");

			$this->load->library('table');
			$this->table->set_template(array ('heading_cell_start'  => '<th style="text-align:left;">'));

			$headerColumns = array('File', 'Type', 'ID', 'Path');
			
			if ($showAll) {
				$headerColumns[] = 'Exists';
			}

			$this->table->set_heading($headerColumns);
				
			foreach($query->result() as $row) {
				$full_path = "{$this->archive_root_path}{$row->path}/{$row->filename}";
				
				if (strlen($filenameFilter) > 0 && strpos(strtolower($row->filename), $filenameFilter) === false) {
					// Skip this file
					continue;
				}
				
				$fileExists = file_exists($full_path);
				
				if ($showAll) {
					$this->table->add_row($row->filename, $row->type, $row->id, $row->path, $fileExists ? "Yes" : "No");
				} else {
					if (!$fileExists) {
						$this->table->add_row($row->filename, $row->type, $row->id, $row->path);
					}
				}
			}

			// Navigation table			
			echo '<table cellpadding="10" style="border: 1px solid"><tr>' . "\n";
			echo '<td><a href="' . site_url() . 'file_attachment/check_access">Missing files</a></td>' . "\n";
			echo '<td><a href="' . site_url() . 'file_attachment/check_access/show_all_files">All files</a></td>' . "\n";
			echo '<td><a href="' . site_url() . 'file_attachment/check_access/xls">Excel files</a></td>' . "\n";
			echo '<td><a href="' . site_url() . 'file_attachment/check_access/txt">Text files</a></td>' . "\n";
			echo '</tr></table>' . "\n";

			// Description of the files shown
			if ($showAll) {
				if (strlen($filenameFilter) > 0)
					echo '<p><font size="+1">"' . $filenameFilter . '" Files</font></p>';
				else
					echo '<p><font size="+1">All Files</font></p>';
			} else {
				echo '<p><font size="+1">Missing Files</font></p>';
			}

			echo $this->table->generate();

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

}
