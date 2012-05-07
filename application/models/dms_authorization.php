<?php
class Dms_authorization extends CI_Model {
		
	var $storage_name = 'dms_authorization';
	var $user_permissions = array();

	var $dBFolder = "application/model_config/";
	
	// --------------------------------------------------------------------
	function __construct() 
	{
		//Call the Model constructor
		parent::__construct();
//		$this->initialize();
	}
	// --------------------------------------------------------------------
	function initialize()
	{
	}

	// --------------------------------------------------------------------
	function get_master_restriction_list()
	{
	 	$dbFilePath = $this->dBFolder."master_authorization.db";
		$table_name = 'restricted_actions';
		$sql = "SELECT * FROM $table_name ORDER BY page_family;";        
		$dbh = new PDO("sqlite:$dbFilePath");
        return $dbh->query($sql, PDO::FETCH_ASSOC);
	}

	// --------------------------------------------------------------------
	function get_controller_action_restrictions($controller, $action)
	{
		$restrictions = array();
	 	$dbFilePath = $this->dBFolder."master_authorization.db";
		$table_name = 'restricted_actions';

        $dbh = new PDO("sqlite:$dbFilePath");
        $stmt = $dbh->query("SELECT * FROM $table_name WHERE page_family = '$controller' AND action = '$action'", PDO::FETCH_ASSOC);
		$row = $stmt->fetch();
		if(!(FALSE === $row)) {
			$restrictions = preg_split('/, */', $row['required_permisions']);
		}
		return $restrictions;
	}
	// --------------------------------------------------------------------
	function get_user_permissions($user_dprn)
	{
		// is there a local cache of permissions?
		if(count($this->user_permissions) > 0) {
			return $this->user_permissions;
		}

		// is there a session cache of permissions?
		if($this->load_defaults()) {
			return $this->user_permissions;			
		}

		// look up user's permission from database
		$p = array();
		$str = '';	
		$str .= <<<EOD
SELECT Status, [Operations List], ID
FROM V_User_List_Report_2
WHERE [Payroll Num] = '$user_dprn'
EOD;
		$my_db = $this->load->database('default', TRUE);
		$query_data = $my_db->query($str);
		if(!$query_data) {
			throw new Exception("Error getting records from database");
		}
		$rows = $query_data->result_array();
		if(count($rows)==0) {
			// user isn't in table - automatically a guest
			$p[] ='DMS_Guest';
		} else 
		if($rows[0]['Status']!='Active') {
			// user is inactive - automatically a guest
			$p[] = 'DMS_Guest';			
		} else {
			// user is in list and active, get their permissions
			$p = preg_split('/, */', $rows[0]['Operations List']);

			// each user gets to have "DMS_User" permission automatically 
			// unless they have "DMS_Guest"
			if(!array_key_exists("DMS_User", $p) && !array_key_exists("DMS_Guest", $p)) {
				$p[] = 'DMS_User';
			}
		}
		// cache the permissions and return them
		$this->user_permissions = $p;
		$this->save_defaults();
		return $p;
	}
	
	// --------------------------------------------------------------------
	// manage saving and reading user permissions from session
	function save_defaults()
	{
		$_SESSION[$this->storage_name] =  serialize($this->user_permissions);
	}
	function load_defaults() 
	{		
		if (isset($_SESSION[$this->storage_name])) {
			$state = $_SESSION[$this->storage_name];
    		$this->user_permissions = unserialize($state);
			return TRUE;
		} else {
			$this->user_permissions = array();
			return FALSE;
		}
    }
	function clear_saved_defaults()
	{
		$this->user_permissions = array();
		unset($_SESSION[$this->storage_name]);
	}

}
?>
