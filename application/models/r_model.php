<?php
// actions and specifications for hot links and other display cell presentations

// main class
class R_model extends CI_Model {
	
	private $config_name = '';
	private $config_source = '';
	private	$configDBFolder = "";
	
	private $list_report_hotlinks = array();
	
	private $detail_report_hotlinks = array();
	
	private $has_checkboxes = FALSE;

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->configDBFolder = $this->config->item('model_config_path');
	}
	
	// --------------------------------------------------------------------
	function init($config_name, $config_source )
	{
		try {
			$this->config_name = $config_name;
			$this->config_source = $config_source;
	
			$dbFileName = $config_source . '.db';

			if($config_name == 'na' or $config_name == '') {
				$this->get_general_defs($config_name, $dbFileName);
			} else {
				$this->get_utility_defs($config_name, $dbFileName);				
			}
			return TRUE;
		} catch (Exception $e) {
			$this->error_text = $e->getMessage();
			return FALSE;
		}
	}

	// --------------------------------------------------------------------
	function has_checkboxes()
	{
		return $this->has_checkboxes;
	}

	// --------------------------------------------------------------------
	function get_list_report_hotlinks()
	{
		return $this->list_report_hotlinks;
	}

	// --------------------------------------------------------------------
	function get_detail_report_hotlinks()
	{
		return $this->detail_report_hotlinks;
	}
	
	// --------------------------------------------------------------------
	private
	function get_general_defs($config_name, $dbFileName)
	{
		$dbFilePath = $this->configDBFolder . $dbFileName;
		
		$dbh = new PDO("sqlite:$dbFilePath");
		if(!$dbh) throw new Exception('Could not connect to config database at '.$dbFilePath);

		// get list of tables in database
		$tbl_list = array();
		foreach ($dbh->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'", PDO::FETCH_ASSOC) as $row) {
			$tbl_list[] = $row['tbl_name'];
		}

		if(in_array('list_report_hotlinks', $tbl_list)) {		
			$this->list_report_hotlinks = array();
			$i = 1;
			foreach ($dbh->query("SELECT * FROM list_report_hotlinks", PDO::FETCH_ASSOC) as $row) {
				$a = array();
				$a['LinkType'] = $row['LinkType'];
				$a['WhichArg'] = $row['WhichArg'];
				$a['Target'] = $row['Target'];
				$a['hid'] = "name='hot_link".$i++."'"; // $row['hid'];
				if($row['LinkType'] == 'color_label') {
					$a['cond'] = json_decode($row['Options'], true);
				}
				if($row['Options']) {
					$a['Options'] = json_decode($row['Options'], true);
				}
				if(array_key_exists('ToolTip', $row) && $row['ToolTip']) {
					$a['ToolTip'] = $row['ToolTip'];
				}
				$this->list_report_hotlinks[$row['name']] = $a;
			}
		}

		if(in_array('detail_report_hotlinks', $tbl_list)) {
			$this->detail_report_hotlinks = array();
			foreach ($dbh->query("SELECT * FROM detail_report_hotlinks", PDO::FETCH_ASSOC) as $row) {
				$a = array();
				$a['LinkType'] = $row['LinkType'];
				$a['WhichArg'] = $row['WhichArg'];
				$a['Target'] = $row['Target'];
				$a['Placement'] = $row['Placement'];
				$a['id'] = $row['id'];
				$opts = (array_key_exists('options', $row)) ? $row['options'] : '';
				$a['Options'] = ($opts)? json_decode($row['options'], true) : null ;
				$this->detail_report_hotlinks[$row['name']] = $a;
			}
		}
		
	}

	// --------------------------------------------------------------------
	private
	function get_utility_defs($config_name, $dbFileName)
	{
		$dbFilePath = $this->configDBFolder . $dbFileName;
		
		$dbh = new PDO("sqlite:$dbFilePath");
		if(!$dbh) throw new Exception('Could not connect to config database at '.$dbFilePath);

		// get list of tables in database
		$tbl_list = array();
		foreach ($dbh->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'", PDO::FETCH_ASSOC) as $row) {
			$tbl_list[] = $row['tbl_name'];
		}

		if(in_array('utility_queries', $tbl_list)) {		

			$sth = $dbh->prepare("SELECT * FROM utility_queries WHERE name='$config_name'");
			$sth->execute();
			$obj = $sth->fetch(PDO::FETCH_OBJ);
			if($obj === FALSE) throw new Exception('Could not find query specs');

			$i = 1;
			$hotlinks = (isset($obj->hotlinks) and $obj->hotlinks != '' )?json_decode($obj->hotlinks, TRUE):array();
			foreach($hotlinks as $name => $spec) {
				$a = array();
				$a['LinkType'] = $spec['LinkType'];
				if($spec['LinkType'] == 'CHECKBOX') $this->has_checkboxes = TRUE;
				$a['WhichArg'] = (array_key_exists('WhichArg', $spec))?$spec['WhichArg']:'value';
				$a['Target'] = (array_key_exists('Target', $spec))?$spec['Target']:'';
				$a['hid'] = "name='hot_link".$i++."'";
				$this->list_report_hotlinks[$name] = $a;
			}
		}
	}

	// --------------------------------------------------------------------
	function get_config_name()
	{
		return $this->config_name;
	}

	// --------------------------------------------------------------------
	function get_config_source()
	{
		return $this->config_source;
	}
	
	
}
?>