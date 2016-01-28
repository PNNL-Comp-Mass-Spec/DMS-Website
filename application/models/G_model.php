<?php
// actions and specifications that apply generally to a page family

// main class
class G_model extends CI_Model {
	
	public $error_text = "";
	
	private $missing_page_family = 'Undefined page family.  Contact the system administrators if this URL should resolve to a valid results page.';
	
	private $config_name = '';
	private $config_source = '';
	private	$configDBFolder = "";
	
	// title templates
	private $titles = array(
			'report' => '@ List Report',
			'search' => '@ List Report',
			'show' => '@ Detail Report',
			'create' => 'Create New @',
			'edit' => 'Edit @',
			'param' => '@',
			'export' => '@ Export',			
			'rss' => '@ Feed',			
		);
	
	// whether actions are allowed, forbidden, or permitted
	private $actions = array(
		'report' => FALSE,
		'show' => FALSE,
		'param' => FALSE,
		'enter' => FALSE,
		'operation' => FALSE,
	);
	
	// collection of all the general param entries
	// contents of genenral_param table from config db are added to this base set
	private $the_parameters = array(
		'has_opener_hotlinks' => FALSE,
		'is_ms_helper' => FALSE,
		'has_checkboxes' => FALSE,
	);
	
	// specs for making post submission links for entry page
	private $post_submission = array('link_tag' => '', 'detail_id' => '', 'link' => '');
	
	
	private $detail_report_commands = array();
	private $detail_report_cmds = '';
	
	private $detail_report_aux_info_target = '';
	
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->configDBFolder = $this->config->item('model_config_path');
	}
	
	// --------------------------------------------------------------------
	function init($config_name, $config_source = "ad_hoc_query")
	{
		$this->error_text = '';
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
	function get_detail_report_aux_info_target()
	{
		return $this->detail_report_aux_info_target;
	}

	// --------------------------------------------------------------------
	function get_page_label($label, $page_type)
	{
		return str_replace('@', $label, $this->titles[$page_type]);
	}

	// --------------------------------------------------------------------
	// for simple "standard" commands to be generated into detailed report page
	function get_detail_report_commands()
	{
		return 	$this->detail_report_commands;
	}
	// --------------------------------------------------------------------
	// for any detail report command files to be loaded into detail report page
	function get_detail_report_cmds()
	{
		return 	$this->detail_report_cmds;
	}

	// --------------------------------------------------------------------
	function get_post_submission_link_specs()
	{
		return 	$this->post_submission;
	}
	
	// --------------------------------------------------------------------
	function get_actions()
	{
		return $this->actions;
	}
	
	// --------------------------------------------------------------------
	private
	function get_utility_defs($config_name, $dbFileName)
	{
		$dbFilePath = $this->configDBFolder . $dbFileName;
		
		if(!file_exists($dbFilePath)) {
			if ($this->configDBFolder)
				throw new Exception($this->missing_page_family . " (see $this->configDBFolder)");
			else
				throw new Exception($this->missing_page_family);
		}
		
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

			$label = (isset($obj->label))?$obj->label:'Page';
			$this->titles['report'] = $label;
			$this->titles['search'] = $label;

			$this->actions['report'] = TRUE;
			
			$this->the_parameters['has_checkboxes'] = !(strpos($obj->hotlinks, 'CHECKBOX') === FALSE);
			if(!(strpos($obj->hotlinks, 'update_opener') === FALSE)) {
				$this->the_parameters['has_opener_hotlinks'] = TRUE;
				$this->the_parameters['is_ms_helper'] = TRUE;;
			}
		}
	}

	// --------------------------------------------------------------------
	private
	function get_general_defs($config_name, $dbFileName)
	{
		$dbFilePath = $this->configDBFolder . $dbFileName;
		
		if(!file_exists($dbFilePath)) {
			if ($this->configDBFolder)
				throw new Exception($this->missing_page_family . " (see $this->configDBFolder)");
			else
				throw new Exception($this->missing_page_family);
		}		
		
		$dbh = new PDO("sqlite:$dbFilePath");
		if(!$dbh) throw new Exception('Could not connect to config database at '.$dbFilePath);

		// get list of tables in database
		$tbl_list = array();
		foreach ($dbh->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'", PDO::FETCH_ASSOC) as $row) {
			$tbl_list[] = $row['tbl_name'];
		}

		// maybe move this to general model?
		foreach ($dbh->query("SELECT * FROM general_params", PDO::FETCH_ASSOC) as $row) {
			
			$this->the_parameters[$row['name']] = $row['value'];
			
			if($row['name'] == 'list_report_helper_multiple_selection') {
				$this->the_parameters['is_ms_helper'] = ($row['value'] == 'yes');
			} else
			if(stripos($row['name'], 'post_submission') !== FALSE) {
				$name = str_replace('post_submission_' , '', $row['name']);
				$this->post_submission[$name] = $row['value'];
			} else
			if(stripos($row['name'], 'alternate_title') !== FALSE) {
				$name = str_replace('alternate_title_' , '', $row['name']);
				$this->titles[$name] = $row['value'];
			} else 
			if($row['name'] == 'detail_report_cmds') {
					$this->detail_report_cmds = $row['value'];
			} else
			if($row['name'] == 'detail_report_aux_info_target') {
					$this->detail_report_aux_info_target = $row['value'];
			} else
			switch($row['name']) {
				case 'list_report_data_table':
					$this->actions['report'] = TRUE;
					break;
				case 'list_report_sproc':
					$this->actions['param'] = TRUE;
					$resolved_action = 'unrestricted';
					break;
				case 'detail_report_data_table':
					$this->actions['show'] = TRUE;
					break;
				case 'entry_sproc':
					$this->actions['enter'] = 'P';
					break;
				case 'operations_sproc':
					$this->actions['operation'] = 'P';
					break;
				default:
					// add root name of any ad hoc sproc to actions list
					if(stripos($row['name'], '_sproc') !== FALSE) {
						$name = str_replace('_sproc' , '', $row['name']);
						$this->actions[$name] = TRUE;
					}
					break;
			}			
		}
		if(in_array('list_report_hotlinks', $tbl_list)) {		
			$this->list_report_hotlinks = array();
			foreach ($dbh->query("SELECT * FROM list_report_hotlinks", PDO::FETCH_ASSOC) as $row) {
				$link_type = $row['LinkType'];
				if($link_type == 'update_opener') {
					$this->the_parameters['has_opener_hotlinks'] = TRUE;
				} else
				if($link_type == 'CHECKBOX') {
					$this->the_parameters['has_checkboxes'] = TRUE;
				}	
			}
		}
		if(in_array('detail_report_commands', $tbl_list)) {
			$this->detail_report_commands = array();
			foreach ($dbh->query("SELECT * FROM detail_report_commands", PDO::FETCH_ASSOC) as $row) {
				$a = array();
				$a['Type'] = $row['Type'];
				$a['Command'] = $row['Command'];
				$a['Target'] = $row['Target'];
				$a['Tooltip'] = $row['Tooltip'];
				$a['Prompt'] = $row['Prompt'];
	
				$this->detail_report_commands[$row['name']] = $a;
			}
		}
	}
	
	//--------------------------------------------------------------------
	function get_param($name)
	{
		return (array_key_exists($name, $this->the_parameters))?$this->the_parameters[$name]:FALSE;
	}

	//--------------------------------------------------------------------
	// Verify (all):
	// - action is allowed for page family
	// - user has at least basic access to website
	// - user has necessary permission if action is a restricted one
	function check_permission($user, $action, $page_family)
	{	
		try {
			if(array_key_exists($action, $this->actions)) {
				$allowed = $this->actions[$action];
			} else throw new exception("Action '$action' is not recognized");

			// not all actions are possible for a given page family
			if($allowed === FALSE) throw new Exception('That action is not allowed');

			// we are going to have to check further, so load the authorization model
			$CI =& get_instance();
			$CI->load->model('dms_authorization', 'auth');

			// get user permissions
			$permissions = $CI->auth->get_user_permissions($user);
			if(empty($permissions)) {
				return "User '$user' does not have any access to the website";
			}
		
			// user will at least need basic access
			$hits = array_intersect(array("DMS_User", "DMS_Guest"), $permissions);
			if(empty($hits)) {
				return "User '$user' does not have general access to the website";
			}

			// free pass from here if action has no restrictions
			if($allowed === TRUE) return TRUE;

			// get list of authorizations required for the action
			$restrictions = $CI->auth->get_controller_action_restrictions($page_family, $action);		
			
			// action has no restrictions, good to go
			if(empty($restrictions)) return TRUE;

			// look for intersection of permissions with restrictions
			$hits = array_intersect($restrictions, $permissions);

			if(empty($hits)) {
				$msg = "";
				$msg .= "Action is restricted to '";
				$msg .= implode  (', ', $restrictions);
				$msg .= "' permissions and user ";
				$msg .= $user;
				$msg .= " has '";
				$msg .= implode  (', ', $permissions);
				$msg .= "' permissions.";
				throw new Exception($msg);	
			}
	
			// made it this far, good to go
			return TRUE;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	
}
?>