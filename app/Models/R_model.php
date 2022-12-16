<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\SQLite3\Connection;

/**
 * Tracks actions and specifications for hot links and other display cell presentations
 */
class R_model extends Model {

    /**
     * Config type, e.g. na for list reports and detail reports;
     * helper_inst_group_dstype for http://dms2.pnl.gov/data/lr/ad_hoc_query/helper_inst_group_dstype/report
     * @var type
     */
    private $config_name = '';

    /**
     * Data source, e.g. dataset, experiment, ad_hoc_query
     * @var type
     */
    private $config_source = '';

    /**
     * Path to the model config database file
     * @var type
     */
    private $configDBPath = "";

    private $list_report_hotlinks = array();
    private $detail_report_hotlinks = array();
    private $has_checkboxes = false;

    // --------------------------------------------------------------------
    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // --------------------------------------------------------------------
    /**
     * Initialize, including reading data from the model config database
     * @param string $config_name Config type; na for list reports and detail reports,
     *                            but a query name like helper_inst_group_dstype when the source is ad_hoc_query
     * @param string $config_source Data source, e.g. dataset, experiment, ad_hoc_query
     * @return boolean
     */
    function init($config_name, $config_source) {
        try {
            $this->config_name = $config_name;
            $this->config_source = $config_source;

            $dbFileName = $config_source . '.db';

            helper(['config_db']);
            $dbFileData = get_model_config_db_path($dbFileName);
            $this->configDBPath = $dbFileData->path;

            if (!$dbFileData->exists) {
                if ($dbFileData->disabled) {
                    throw new \Exception("The config database file '$dbFileName' is disabled in folder '$dbFileData->dirPath'");
                } elseif ($dbFileData->dirPath) {
                    throw new \Exception("The config database file '$dbFileName' does not exist in folder '$dbFileData->dirPath'");
                } else {
                    throw new \Exception("The config database file '$dbFileName' does not exist");
                }
            }

            if ($config_name == 'na' || $config_name == '') {
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
    function has_checkboxes() {
        return $this->has_checkboxes;
    }

    // --------------------------------------------------------------------
    function get_list_report_hotlinks() {
        return $this->list_report_hotlinks;
    }

    // --------------------------------------------------------------------
    function get_detail_report_hotlinks() {
        return $this->detail_report_hotlinks;
    }

    /**
     * Read data from tables list_report_hotlinks and detail_report_hotlinks
     * in a model config database
     * @param string $config_name
     * @throws Exception
     */
    private function get_general_defs($config_name) {
        $db = new Connection(['database' => $this->configDBPath, 'dbdriver' => 'sqlite3']);
        //$dbh = new PDO("sqlite:$this->configDBPath");
        //if (!$dbh) {
        //    throw new \Exception('Could not connect to config database at ' . $this->configDBPath);
        //}

        // get list of tables in database
        $tbl_list = array();
        //foreach ($dbh->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'", PDO::FETCH_ASSOC) as $row) {
        foreach ($db->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'")->getResultArray() as $row) {
            $tbl_list[] = $row['tbl_name'];
        }

        $url_updater = new \App\Libraries\URL_updater();

        if (in_array('list_report_hotlinks', $tbl_list)) {
            $this->list_report_hotlinks = array();
            $i = 1;
            //foreach ($dbh->query("SELECT * FROM list_report_hotlinks", PDO::FETCH_ASSOC) as $row) {
            foreach ($db->query("SELECT * FROM list_report_hotlinks")->getResultArray() as $row) {
                $a = array();
                $a['LinkType'] = $row['LinkType'];
                $a['WhichArg'] = $row['WhichArg'];
                $a['Target'] = $url_updater->fix_link($row['Target']);
                $a['hid'] = "name='hot_link" . $i++ . "'"; // $row['hid'];
                if ($row['LinkType'] == 'color_label') {
                    $a['cond'] = json_decode($row['Options'], true);
                }
                if ($row['Options']) {
                    $a['Options'] = json_decode($row['Options'], true);
                }
                if (array_key_exists('ToolTip', $row) && $row['ToolTip']) {
                    $a['ToolTip'] = $row['ToolTip'];
                }
                $this->list_report_hotlinks[$row['name']] = $a;
            }
        }

        if (in_array('detail_report_hotlinks', $tbl_list)) {
            $this->detail_report_hotlinks = array();
            //foreach ($dbh->query("SELECT * FROM detail_report_hotlinks", PDO::FETCH_ASSOC) as $row) {
            foreach ($db->query("SELECT * FROM detail_report_hotlinks")->getResultArray() as $row) {
                $a = array();
                $a['LinkType'] = $row['LinkType'];
                $a['WhichArg'] = $row['WhichArg'];
                $a['Target'] = $url_updater->fix_link($row['Target']);
                $a['Placement'] = $row['Placement'];
                $a['id'] = $row['id'];
                $opts = (array_key_exists('options', $row)) ? $row['options'] : '';
                $a['Options'] = ($opts) ? json_decode($row['options'], true) : null;
                $this->detail_report_hotlinks[$row['name']] = $a;
            }
        }

        $db->close();
    }

    // --------------------------------------------------------------------
    /**
     * Read data from table utility_queries, for example, with the ad_hoc_query page family
     * @param string $config_name
     * @throws Exception
     */
    private function get_utility_defs($config_name) {
        $db = new Connection(['database' => $this->configDBPath, 'dbdriver' => 'sqlite3']);
        //$dbh = new PDO("sqlite:$this->configDBPath");
        //if (!$dbh) {
        //    throw new \Exception('Could not connect to config database at ' . $this->configDBPath);
        //}

        // get list of tables in database
        $tbl_list = array();
        //foreach ($dbh->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'", PDO::FETCH_ASSOC) as $row) {
        foreach ($db->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'")->getResultArray() as $row) {
            $tbl_list[] = $row['tbl_name'];
        }

        if (in_array('utility_queries', $tbl_list)) {

            //$sth = $dbh->prepare("SELECT * FROM utility_queries WHERE name='$config_name'");
            //$sth->execute();
            //$obj = $sth->fetch(PDO::FETCH_OBJ);            
            $obj = $db->query("SELECT * FROM utility_queries WHERE name='$config_name'")->getRowObject();
            if ($obj === false || is_null($obj)) {
                throw new \Exception('Could not find query specs');
            }

            $i = 1;
            $hotlinks = (isset($obj->hotlinks) and $obj->hotlinks != '' ) ? json_decode($obj->hotlinks, true) : array();
            foreach ($hotlinks as $name => $spec) {
                $a = array();
                $a['LinkType'] = $spec['LinkType'];
                if ($spec['LinkType'] == 'CHECKBOX') {
                    $this->has_checkboxes = true;
                }
                $a['WhichArg'] = (array_key_exists('WhichArg', $spec)) ? $spec['WhichArg'] : 'value';
                $a['Target'] = (array_key_exists('Target', $spec)) ? $spec['Target'] : '';
                $a['hid'] = "name='hot_link" . $i++ . "'";
                $this->list_report_hotlinks[$name] = $a;
            }
        }

        $db->close();
    }

    // --------------------------------------------------------------------
    function get_config_name() {
        return $this->config_name;
    }

    // --------------------------------------------------------------------
    function get_config_source() {
        return $this->config_source;
    }
}
?>
