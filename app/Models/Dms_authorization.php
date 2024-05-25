<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\SQLite3\Connection;

class Dms_authorization extends Model {

    var $storage_name = 'dms_authorization';
    var $user_permissions = array();
    var $masterAuthDBPath = "";

    // --------------------------------------------------------------------
    function __construct() {
        //Call the Model constructor
        parent::__construct();

        helper(['config_db']);
        $this->masterAuthDBPath = get_model_config_db_path("master_authorization.db")->path;
//      $this->initialize();
    }

    // --------------------------------------------------------------------
    function initialize() {

    }

    /**
     * Read the restricted actions defined in the master_authorization SQLite database
     * @return mixed Rows of data
     */
    function get_master_restriction_list() {
        $table_name = 'restricted_actions';
        $sql = "SELECT * FROM $table_name ORDER BY page_family;";
        $db = new Connection(['database' => $this->masterAuthDBPath, 'dbdriver' => 'sqlite3']);
        $data = $db->query($sql)->getResultArray();
        $db->close();
        return $data;
    }

    /**
     * Lookup restricted actions for the given controller
     * @param string $controller
     * @param string $action
     * @return array
     */
    function get_controller_action_restrictions($controller, $action) {
        $restrictions = array();
        $table_name = 'restricted_actions';

        $db = new Connection(['database' => $this->masterAuthDBPath, 'dbdriver' => 'sqlite3']);
        $row = $db->query("SELECT * FROM $table_name WHERE page_family = '$controller' AND action = '$action'")->getRowArray();
        if (!(false === $row || is_null($row))) {
            $restrictions = preg_split('/, */', $row['required_permisions']);
        }

        $db->close();
        return $restrictions;
    }

    /**
     * Lookup permissions for the user
     * @param type $username
     * @return string
     * @throws Exception
     */
    function get_user_permissions($username) {
        // Is there a local cache of permissions?
        if (count($this->user_permissions) > 0) {
            return $this->user_permissions;
        }

        // Is there a session cache of permissions?
        if ($this->load_defaults()) {
            return $this->user_permissions;
        }

        helper(['database']);

        // Look up user's permission from database
        $p = array();
        $str = '';
        $str .= <<<EOD
SELECT status, operations_list, id
FROM V_User_Operation_Export
WHERE username = '$username'
EOD;

        $my_db = \Config\Database::connect('default');
        update_search_path($my_db);
        $query_data = $my_db->query($str);
        if (!$query_data) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for user permissions; see writable/logs/log-$currentTimestamp.php");
        }
        $rows = $query_data->getResultArray();

        if (count($rows) == 0) {
            // User isn't in table - automatically a guest
            $p[] = 'DMS_Guest';
        } else
        if ($rows[0]['status'] != 'Active') {
            // User is inactive - automatically a guest
            $p[] = 'DMS_Guest';
        } else {
            // User is in list and active, get their permissions
            $p = preg_split('/, */', $rows[0]['operations_list']);

            // Each user gets to have "DMS_User" permission automatically
            // unless they have "DMS_Guest"
            if (!array_key_exists("DMS_User", $p) && !array_key_exists("DMS_Guest", $p)) {
                $p[] = 'DMS_User';
            }
        }

        // Cache the permissions and return them
        $this->user_permissions = $p;
        $this->save_defaults();
        return $p;
    }

    /**
     * Save user permissions for session
     */
    function save_defaults() {
        $_SESSION[$this->storage_name] = serialize($this->user_permissions);
    }

    /**
     * Load user permissions for session
     * @return boolean True if cached user permissions were found
     */
    function load_defaults() {
        if (isset($_SESSION[$this->storage_name])) {
            $state = $_SESSION[$this->storage_name];
            $this->user_permissions = unserialize($state);
            return true;
        } else {
            $this->user_permissions = array();
            return false;
        }
    }

    /**
     * Clear cached user permissions
     */
    function clear_saved_defaults() {
        $this->user_permissions = array();
        unset($_SESSION[$this->storage_name]);
    }
}
?>
