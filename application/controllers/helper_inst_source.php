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
	function view($inst = "")
	{
		$this->load->helper(array('url', 'string'));

		if (empty($inst)) {
		
			echo "<p>Commonly used DMS Instruments</p>";
			echo "<ul>";
			$instruments = array();
			$instruments[] = "12T_FTICR_B";
			$instruments[] = "15T_FTICR";
			$instruments[] = "15T_FTICR_Imaging";
			$instruments[] = "7T_FTICR_B";
			$instruments[] = "Agilent_GC_MS_01";
			$instruments[] = "CBSS_Orb1";
			$instruments[] = "Exact03";
			$instruments[] = "Exact04";
			$instruments[] = "IMS04_AgTOF05";
			$instruments[] = "IMS07_AgTOF04";
			$instruments[] = "IMS08_AgQTOF05";
			$instruments[] = "LTQ_2";
			$instruments[] = "LTQ_4";
			$instruments[] = "LTQ_Orb_1";
			$instruments[] = "LTQ_Orb_2";
			$instruments[] = "LTQ_Orb_3";
			$instruments[] = "Maxis_01";
			$instruments[] = "QExactHF03";
			$instruments[] = "QExactP02";
			$instruments[] = "TIMS_Maxis";
			$instruments[] = "TSQ_3";
			$instruments[] = "TSQ_4";
			$instruments[] = "TSQ_5";
			$instruments[] = "TSQ_6";
			$instruments[] = "VOrbi05";
			$instruments[] = "VOrbiETD01";
			$instruments[] = "VOrbiETD02";
			$instruments[] = "VOrbiETD03";
			$instruments[] = "VOrbiETD04";
			
			foreach ($instruments as $instrument)
			{
				echo "<li><a href=\"/helper_inst_source/view/" . $instrument . "\">" . $instrument. "</a></li>";
			}
				
		    exit;
		}
		
		// get source content file from gigasax website
		$file = fopen ("http://gigasax.pnl.gov/DMS_Inst_Source/".$inst."_source.txt", "r");
		if (!$file) {
		    echo "<p>Unable to open source file.</p>";
			echo "<p>See the list of <a href=\"/helper_inst_source/view/\">commonly used DMS instruments</a></li>";
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
			
			// make name into link, as appropriate (file or dir)
			// Do not link items that start with x_ or that end with .sld
			if (($type == 'File' || $type == 'Dir') && 
			    !preg_match("/^x_/i", $valueClean) &&
			    !preg_match("/\.(sld|meth|txt|log)$/i", $value)
			   ) 
			{
				$lnk = "<a href='javascript:opener.epsilon.updateFieldValueFromChooser(\"$valueClean\", \"replace\")' >$value</a>";
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
					$dirInfo = "$lnk ($type";
					if(strlen($size) > 0)
						$dirInfo .= ", " . $size;

					$dirs[] = $dirInfo . ")";
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