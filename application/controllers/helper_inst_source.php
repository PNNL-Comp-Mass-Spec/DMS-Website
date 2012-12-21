<?php
require("base_controller.php");

class helper_inst_source extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "helper_inst_source";
//		$this->my_model = "";
		$this->my_title = "Source Files for Datasets";
		$this->my_list_action = "";
		$this->my_export_action = "";
	}

	// --------------------------------------------------------------------
	// present file contents with chooser links
	function view($inst)
	{
		$this->load->helper(array('url', 'string'));

		// get source content file from gigasax website
		$file = fopen ("http://gigasax.pnl.gov/DMS_Inst_Source/".$inst."_source.txt", "r");
		if (!$file) {
		    echo "<p>Unable to open source file.\n";
		    exit;
		}

		$data['title'] = $this->my_title;
		$data['heading'] = "Source Files for $inst";

		// open instrument source file and
		// read lines and select and prepare as appropriate
		// and stuff into results array
		$files = array();
		$dirs = array();
		$other = array();
		while (!feof ($file)) {
		    $line = fgets ($file, 1024);
			// skip blank lines
			if(preg_match("/^\s*$/", $line)) continue;
			$flds = preg_split('/[\t]/', $line);
			$type = trim($flds[0]);
			$value = trim($flds[1]);

			// If three fields are present, assume the 3rd field is file size
			if(count($flds) > 2)
				$size = trim($flds[2]);
			else
				$size = "";

			// clean off file extensions
			$valueClean = preg_replace('/(\.raw$|\.wiff$|\.d$)/i'  , '', $value );
			
			// make name into link, as appropriate (file or dir and no links for x_ marked)
			if (($type == 'File' || $type == 'Dir') &&  !preg_match("/^x_/i", $valueClean)) {
				$lnk = "<a href='javascript:opener.gamma.updateFieldValueFromChooser(\"$valueClean\", \"replace\")' >$value</a>";
			} else {
				$lnk = $value;
			}
			// put into proper category
			switch($type) {
				case 'File':
					$fileInfo = "$lnk ($type";
					if(strlen($size) > 0)
						$fileInfo .= ", " . $size;

					$files[] = $fileInfo . ")";
					break;
				case 'Dir':
					$dirs[] = "$lnk ($type)";
					break;
				default:
					$other[] = "$lnk ($type)";
					break;
			}
		}
		fclose($file);

		$result = array_merge($other, $dirs, $files);
		$data['result'] = $result;

		// load up data array and call view template
		$this->load->vars($data);
		$this->load->view('simple_list');
	}

}
?>