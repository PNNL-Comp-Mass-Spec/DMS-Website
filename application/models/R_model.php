<?php

/**
 * Tracks actions and specifications for hot links and other display cell presentations
 */
class R_model extends CI_Model {

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
    private $configDBFolder = "";
    private $list_report_hotlinks = array();
    private $detail_report_hotlinks = array();
    private $has_checkboxes = FALSE;

    // --------------------------------------------------------------------
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->configDBFolder = $this->config->item('model_config_path');
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

            if ($config_name == 'na' || $config_name == '') {
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
     * @param string $dbFileName
     * @throws Exception
     */
    private function get_general_defs($config_name, $dbFileName) {
        $dbFilePath = $this->configDBFolder . $dbFileName;

        if (!file_exists($dbFilePath)) {
            if ($this->configDBFolder) {
                throw new Exception("The config database file '$dbFileName' does not exist in folder '$this->configDBFolder'");
            } else {
                throw new Exception("The config database file '$dbFileName' does not exist");
            }
        }

        $dbh = new PDO("sqlite:$dbFilePath");
        if (!$dbh) {
            throw new Exception('Could not connect to config database at ' . $dbFilePath);
        }

        // get list of tables in database
        $tbl_list = array();
        foreach ($dbh->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'", PDO::FETCH_ASSOC) as $row) {
            $tbl_list[] = $row['tbl_name'];
        }

        if (in_array('list_report_hotlinks', $tbl_list)) {
            $this->list_report_hotlinks = array();
            $protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ? "https" : "http";
            $server_bionet = stripos($_SERVER["SERVER_NAME"], "bionet") !== false;
            $i = 1;
            foreach ($dbh->query("SELECT * FROM list_report_hotlinks", PDO::FETCH_ASSOC) as $row) {
                $a = array();
                $a['LinkType'] = $row['LinkType'];
                $a['WhichArg'] = $row['WhichArg'];
                $a['Target'] = $row['Target'];
                if ($server_bionet && stripos($a['Target'], "http") === 0) {
                    $target_host = str_ireplace(".emsl.pnl.gov", ".bionet", $a['Target']);
                    $target_host = str_ireplace(".pnl.gov", ".bionet", $target_host);
                    $prev_protocol = stripos($target_host, "https") === 0 ? "https" : "http";
                    if ($prev_protocol !== $protocol) {
                        $target_host = str_ireplace($prev_protocol, $protocol, $target_host);
                    }
                    $a['Target'] = $target_host;
                }
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
            foreach ($dbh->query("SELECT * FROM detail_report_hotlinks", PDO::FETCH_ASSOC) as $row) {
                $a = array();
                $a['LinkType'] = $row['LinkType'];
                $a['WhichArg'] = $row['WhichArg'];
                $a['Target'] = $row['Target'];
                $a['Placement'] = $row['Placement'];
                $a['id'] = $row['id'];
                $opts = (array_key_exists('options', $row)) ? $row['options'] : '';
                $a['Options'] = ($opts) ? json_decode($row['options'], true) : null;
                $this->detail_report_hotlinks[$row['name']] = $a;
            }
        }
    }

    // --------------------------------------------------------------------
    /**
     * Read data from table utility_queries, for example, with the ad_hoc_query page family
     * @param string $config_name
     * @param string $dbFileName
     * @throws Exception
     */
    private function get_utility_defs($config_name, $dbFileName) {
        $dbFilePath = $this->configDBFolder . $dbFileName;

        $dbh = new PDO("sqlite:$dbFilePath");
        if (!$dbh) {
            throw new Exception('Could not connect to config database at ' . $dbFilePath);
        }

        // get list of tables in database
        $tbl_list = array();
        foreach ($dbh->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'", PDO::FETCH_ASSOC) as $row) {
            $tbl_list[] = $row['tbl_name'];
        }

        if (in_array('utility_queries', $tbl_list)) {

            $sth = $dbh->prepare("SELECT * FROM utility_queries WHERE name='$config_name'");
            $sth->execute();
            $obj = $sth->fetch(PDO::FETCH_OBJ);
            if ($obj === FALSE) {
                throw new Exception('Could not find query specs');
            }

            $i = 1;
            $hotlinks = (isset($obj->hotlinks) and $obj->hotlinks != '' ) ? json_decode($obj->hotlinks, TRUE) : array();
            foreach ($hotlinks as $name => $spec) {
                $a = array();
                $a['LinkType'] = $spec['LinkType'];
                if ($spec['LinkType'] == 'CHECKBOX') {
                    $this->has_checkboxes = TRUE;
                }
                $a['WhichArg'] = (array_key_exists('WhichArg', $spec)) ? $spec['WhichArg'] : 'value';
                $a['Target'] = (array_key_exists('Target', $spec)) ? $spec['Target'] : '';
                $a['hid'] = "name='hot_link" . $i++ . "'";
                $this->list_report_hotlinks[$name] = $a;
            }
        }
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
