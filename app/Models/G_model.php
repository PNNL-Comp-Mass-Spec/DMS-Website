<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\SQLite3\Connection;

/**
 * Actions and specifications that apply generally to a page family
 */
class G_model extends Model {

    public $error_text = "";

    private $missing_page_family = 'Undefined page family.  Contact the system administrators if this URL should resolve to a valid results page.';
    private $disabled_page_family = 'Disabled page family.  Contact the system administrators if this URL should resolve to a valid results page.';

    private $config_name = '';
    private $config_source = '';
    private $configDBPath = "";

    /**
     * Title templates
     * @var array
     */
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

    /**
     * Whether actions are allowed, forbidden, or permitted
     * Values are True if allowed, False if forbidden, and P if "permitted"
     *
     * Permitted actions require that the user to be in a user group that has the granted permission,
     * as tracked in the master_authorization database.  See function check_permission
     *
     * @var array
     */
    private $actions = array(
        'report' => false,
        'show' => false,
        'param' => false,
        'enter' => false,       // Edit an existing entry
        'operation' => false,
        'create' => false       // Create a new entry (via New or Copy)
    );

    /**
     * Collection of all the general param entries
     * Contents of general_param table from config db are added to this base set
     * @var array
     */
    private $the_parameters = array(
        'has_opener_hotlinks' => false,
        'is_ms_helper' => false,
        'has_checkboxes' => false,
    );

    /**
     * Specs for making post submission links for entry page
     * @var array
     */
    private $post_submission = array('link_tag' => '', 'detail_id' => '', 'link' => '');


    private $detail_report_commands = array();
    private $detail_report_cmds = '';

    private $detail_report_aux_info_target = '';

    private $list_report_sort_persist_enabled = true;

    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    // --------------------------------------------------------------------
    function init($config_name, $config_source = "ad_hoc_query")
    {
        $this->error_text = '';
        try {
            $this->config_name = $config_name;
            $this->config_source = $config_source;

            $dbFileName = $config_source . '.db';

            helper(['config_db']);
            $dbFileData = get_model_config_db_path($dbFileName);
            $this->configDBPath = $dbFileData->path;

            if (!$dbFileData->exists) {
                if ($dbFileData->disabled) {
                    throw new \Exception($this->disabled_page_family . " (see '$dbFileData->dirPath')");
                } elseif ($dbFileData->dirPath) {
                    throw new \Exception($this->missing_page_family . " (see '$dbFileData->dirPath')");
                } else {
                    throw new \Exception($this->missing_page_family);
                }
            }

            if($config_name == 'na' || $config_name == '') {
                $this->get_general_defs($config_name);
            } else {
                $this->get_utility_defs($config_name);
            }
            return true;
        } catch (\Exception $e) {
            $this->error_text = $e->getMessage();
            return false;
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

    /**
     * For simple "standard" commands to be generated into detailed report page
     * @return type
     */
    function get_detail_report_commands()
    {
        return  $this->detail_report_commands;
    }

    /**
     * For any detail report command files to be loaded into detail report page
     * @return type
     */
    function get_detail_report_cmds()
    {
        return  $this->detail_report_cmds;
    }

    // --------------------------------------------------------------------
    function get_post_submission_link_specs()
    {
        return  $this->post_submission;
    }

    // --------------------------------------------------------------------
    function get_actions()
    {
        return $this->actions;
    }

    function get_list_report_sort_persist_enabled()
    {
        return $this->list_report_sort_persist_enabled;
    }

    // --------------------------------------------------------------------
    private function get_utility_defs($config_name)
    {
        $db = new Connection(['database' => $this->configDBPath, 'dbdriver' => 'sqlite3']);

        // get list of tables in database
        $tbl_list = array();
        foreach ($db->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'")->getResultArray() as $row) {
            $tbl_list[] = $row['tbl_name'];
        }

        if(in_array('utility_queries', $tbl_list)) {

            $obj = $db->query("SELECT * FROM utility_queries WHERE name='$config_name'")->getRowObject();
            if($obj === false || is_null($obj)) {
                throw new \Exception('Could not find query specs');
            }

            $label = (isset($obj->label))?$obj->label:'Page';
            $this->titles['report'] = $label;
            $this->titles['search'] = $label;

            $this->actions['report'] = true;

            $this->the_parameters['has_checkboxes'] = !(strpos($obj->hotlinks, 'CHECKBOX') === false);
            if(!(strpos($obj->hotlinks, 'update_opener') === false)) {
                $this->the_parameters['has_opener_hotlinks'] = true;
                $this->the_parameters['is_ms_helper'] = true;
            }
        }

        $db->close();
    }

    /**
     * Read contents of general_params and update $this->the_parameters
     * Read list_report_hotlinks and update has_opener_hotlinks and has_checkboxes in $this->the_parameters
     * Read detail_report_commands and store in $this->detail_report_commands
     * @param type $config_name
     * @throws Exception
     */
    private function get_general_defs($config_name)
    {
        $db = new Connection(['database' => $this->configDBPath, 'dbdriver' => 'sqlite3']);

        // get list of tables in database
        $tbl_list = array();
        foreach ($db->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'")->getResultArray() as $row) {
            $tbl_list[] = $row['tbl_name'];
        }

        $allowCreate = false;
        $blockCreate = false;

        // maybe move this to general model?
        foreach ($db->query("SELECT * FROM general_params")->getResultArray() as $row) {

            $this->the_parameters[$row['name']] = $row['value'];

            if($row['name'] == 'list_report_helper_multiple_selection') {
                $this->the_parameters['is_ms_helper'] = ($row['value'] == 'yes');
            } else
            if(stripos($row['name'], 'post_submission') !== false) {
                // post_submission_link, post_submission_detail_id, or post_submission_link_tag
                $name = str_replace('post_submission_' , '', $row['name']);
                $this->post_submission[$name] = $row['value'];
            } else
            if(stripos($row['name'], 'alternate_title') !== false) {
                $name = str_replace('alternate_title_' , '', $row['name']);
                $this->titles[$name] = $row['value'];
            } else
            if($row['name'] == 'detail_report_cmds') {
                $this->detail_report_cmds = $row['value'];
            } else
            if($row['name'] == 'detail_report_aux_info_target') {
                $this->detail_report_aux_info_target = $row['value'];
            } else
            if($row['name'] == 'list_report_disable_sort_persist') {
                if ($row['value'] !== false) {
                    $this->list_report_sort_persist_enabled = false;
                }
            } else
            {
                switch($row['name']) {
                    case 'list_report_data_table':
                        // List report table (or view) is defined
                        $this->actions['report'] = true;
                        break;
                    case 'list_report_sproc':
                        // List report table (or view) is defined
                        $this->actions['param'] = true;
                        break;
                    case 'detail_report_data_table':
                    case 'detail_report_sproc':
                        // Detail report stored procedure is defined (for editing / creating entities)
                        $this->actions['show'] = true;
                        break;
                    case 'entry_sproc':
                        // Only allow this action if it is permitted
                        $this->actions['enter'] = 'P';
                        $allowCreate = true;
                        break;
                    case 'entry_block_new':
                        // If this value evaluates to True by PHP, prevent the user
                        // from using the New or Copy buttons to create a new item
                        if ($row['value']) {
                            $blockCreate = true;
                        }
                        break;
                    case 'operations_sproc':
                        // Only allow this action if it is permitted
                        $this->actions['operation'] = 'P';
                        break;
                    default:
                        // add root name of any ad hoc sproc to actions list
                        if(stripos($row['name'], '_sproc') !== false) {
                            $name = str_replace('_sproc' , '', $row['name']);
                            $this->actions[$name] = true;
                        }
                        break;
                }
            }
        }

        $this->actions['create'] = $allowCreate && !$blockCreate;

        if(in_array('list_report_hotlinks', $tbl_list)) {
            $this->list_report_hotlinks = array();
            foreach ($db->query("SELECT * FROM list_report_hotlinks")->getResultArray() as $row) {
                $link_type = $row['LinkType'];
                if($link_type == 'update_opener') {
                    $this->the_parameters['has_opener_hotlinks'] = true;
                } else
                if($link_type == 'CHECKBOX') {
                    $this->the_parameters['has_checkboxes'] = true;
                }
            }
        }

        if(in_array('detail_report_commands', $tbl_list)) {
            $this->detail_report_commands = array();
            foreach ($db->query("SELECT * FROM detail_report_commands")->getResultArray() as $row) {
                $a = array();
                $a['Type'] = $row['Type'];
                $a['Command'] = $row['Command'];
                $a['Target'] = $row['Target'];
                $a['Tooltip'] = $row['Tooltip'];
                $a['Prompt'] = $row['Prompt'];

                $this->detail_report_commands[$row['name']] = $a;
            }
        }

        $db->close();
    }

    /**
     * Get the value for the specified parameter
     * @param type $name
     * @return type
     */
    function get_param($name)
    {
        return (array_key_exists($name, $this->the_parameters))?$this->the_parameters[$name]:false;
    }

    /**
     * Validate permissions
     * Verify (all):
     * - action is allowed for page family
     * - user has at least basic access to website
     * - user has necessary permission if action is a restricted one
     * @param string $user
     * @param string $action
     * @param string $page_family
     * @param object $controller
     * @return boolean
     * @throws exception
     * @throws Exception
     */
    function check_permission($user, $action, $page_family, $controller)
    {
        try {
            if(array_key_exists($action, $this->actions)) {
                $allowed = $this->actions[$action];
            } else {
                throw new \Exception("Action '$action' is not recognized");
            }

            // not all actions are possible for a given page family
            if($allowed === false) {
                throw new \Exception("Action '$action' is not allowed for this page");
            }

            // we are going to have to check further, so load the authorization model
            $controller->auth = model('App\Models\Dms_authorization');

            // get user permissions
            $permissions = $controller->auth->get_user_permissions($user);
            if(empty($permissions)) {
                return "User '$user' does not have any access to the website";
            }

            // user will at least need basic access
            $hits = array_intersect(array("DMS_User", "DMS_Guest"), $permissions);
            if(empty($hits)) {
                return "User '$user' does not have general access to the website";
            }

            /*
             * Disabled in September 2016 to allow Show and Report permissions to work again
             *
                // free pass from here if action has no restrictions
                if($allowed === true) {
                    return true;
                }
            */

            // get list of authorizations required for the action
            $restrictions = $controller->auth->get_controller_action_restrictions($page_family, $action);

            // action has no restrictions, good to go
            if(empty($restrictions)) {
                return true;
            }

            // look for intersection of permissions with restrictions
            $restrictionHits = array_intersect($restrictions, $permissions);

            if(empty($restrictionHits)) {
                $msg = "";
                $msg .= "Action is restricted to <code>'";
                $msg .= implode  (', ', $restrictions);
                $msg .= "'</code> permissions <br /> and user ";
                $msg .= $user;
                $msg .= " has <code>'";
                $msg .= implode  (', ', $permissions);
                $msg .= "'</code> permissions.<br /><br /> ";
                $msg .= 'To request additional access, e-mail Matthew Monroe<br /> ';
                $msg .= 'or use the Proteomics Queue link on the <a href="/">DMS home page</a>.';
                throw new \Exception($msg);
            }

            // made it this far, good to go
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
?>
