<?php

/**
 * This class is used to edit model config DBs
 */
class Config_db extends CI_Controller {

    /**
     * Unused, but needed for Generic_Controller
     * @var string
     */
    var $my_tag = "";
    var $configDBFolder = '';
    var $configDBPath = '';

    /**
     * Constructor
     */
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        session_start();
        $this->load->helper(array('url', 'string'));

        $this->configDBPath = $this->config->item('model_config_path');

        $CI = &get_instance();
        $this->mod_enabled = $CI->config->item('modify_config_db_enabled');

        $this->load->model('config_db_model', 'config_model');
    }

    /**
     * Redirect http://dms2.pnl.gov/config_db/
     * to http://dms2.pnl.gov/config_db/page_families
     */
    function index()
    {
        $this->load->helper(array('url'));
        redirect('config_db/page_families');
    }

    /**
     * Show contents of the config DB
     * @param string $config_db Config DB name, including .db
     */
    function show_db($config_db)
    {
        $this->load->helper(array('config_db'));
        $data['title'] = "$config_db";
        $data['heading'] = "Show Contents of $config_db";
        $data['config_db'] = $config_db;

        $data['make_controller_control'] = $this->_make_controller_control($config_db);
        $data['make_main_db_sql_control'] = $this->_make_main_db_sql_control($config_db);

        $tbl_list = $this->_get_db_table_list($config_db);

        // dump contents of each table
        $s = $this->_get_table_dump_all($config_db, $tbl_list);

        $data['tables'] = $s;
        $this->load->vars($data);
        $this->load->view('config_db/show_db');
    }

    /**
     * Show the link to config_db/code_for_family_sql/configDBName
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _make_main_db_sql_control($config_db)
    {
        $s = "";
        if ($this->mod_enabled) {
            $tip = "Show SQL for making DMS database objects that match general_params names";
            $link = site_url()."config_db/code_for_family_sql/".$config_db;
            $s .= "<a href='$link' title='$tip'>Suggest SQL to make DMS database objects</a>";
        }
        return $s;
    }

    /**
     * Show the Make Controller or Controller Exists link
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _make_controller_control($config_db)
    {
        $s = "";
        if ($this->mod_enabled) {
            $page_fam_tag = "";
            $file_path = "";
            if (!$this->_controller_exists($config_db, $page_fam_tag, $file_path)) {
                $s = "<a href='javascript:make_controller()'>Make Controller</a>";
            } else {
                $s .= "Controller Exists";
            }
        }
        return $s;
    }

    /**
     * Show the config DB contents after applying a change
     * @param string $config_db Config DB name, including .db
     * @return type
     * @category AJAX
     */
    function submit_show_db($config_db)
    {
        if (!$this->mod_enabled) {
            $this->show_not_allowed($config_db);
            return;
        }
        $tbl_list = $this->_get_db_table_list($config_db);

        // dump contents of each table
        $s = $this->_get_table_dump_all($config_db, $tbl_list);

        $data['tables'] = $s;
        $this->load->vars($data);
        $this->load->view('config_db/sub_show_db');
    }

    /**
     * Make the link to the contents page for this page family, for example
     * <a href="http://dmsdev.pnl.gov/config_db/show_db/dataset.db">Contents</a>
     * @param string $config_db Config DB name, including .db
     * @param string $link_title Link title
     * @return string
     */
    private
    function _make_page_family_contents_link($config_db, $link_title = "Contents")
    {
        return '<a href="' . site_url() . "config_db/show_db/" . $config_db . '">' . "$link_title</a>";
    }

    /**
     * Make the link to the corresponding help page on PrismWiki
     * @param string $table_name Table name
     * @return string
     */
    private
    function _make_wiki_help_link($table_name)
    {
        $s = "";
    //  $CI =& get_instance();
    //  $ptrac = $CI->config->item('ptrac');
    //  $trac_helpLink_prefix = $CI->config->item('tracHelpLinkPrefix');

        $wikiBaseUrl = $this->config->item('pwiki');
        $wiki_helpLink_prefix = "DMS_Config_DB_Help_";
        $href = $wikiBaseUrl.$wiki_helpLink_prefix.$table_name;
    //  $src = base_url(). "/images/help.png";
        $s .= "<a class='help_link' target='_blank' title='Click for help with table' href='$href'>Help</a>";
        return $s;
    }

    /**
     * Execute the given SQL against the model config DB
     * @param strgin $config_db Config DB name, including .db
     * @param string $sql SQL Query (typically an UPDATE or INSERT query)
     * @param string $table_name Target table name
     * @return type
     */
    private
    function _exec_sql($config_db, $sql, $table_name)
    {

        // Script out the existing table contents
        $restore = $this->_get_table_contents_sql($config_db, $table_name);

        $s = "";
        $dbFilePath = $this->configDBPath.$config_db;
        $dbh = new PDO("sqlite:$dbFilePath");
        if (!$dbh) {
            $s .= 'Could not connect to config database at '.$dbFilePath;
            return null;
        }
        $sqlWithTransaction = "BEGIN TRANSACTION; $sql COMMIT;";

        //echo $sqlWithTransaction;
        $dbh->exec($sqlWithTransaction);

        $this->_log_sql($sql, $restore, $config_db);
    }

    /**
     * Log the changes to a file in the tmpfiles directory
     * @param string $change SQL that was applied
     * @param string $restore Old table contents before applying the change
     * @param string $config_db Config DB name, including .db
     */
    private
    function _log_sql($change, $restore, $config_db)
    {
        $this->load->helper(array('user'));
        $usr = get_user();
        $dt = date(DATE_RFC822);
        $file = 'tmpfiles/'.$config_db.'.log';

        $header = "$dt  $usr";

        /*
         * Example log entry:
            [ENTRY]
            Mon, 18 Jul 16 18:17:13 -0700  D3L243
            [restore]
            -- helper_aj_settings_file.db;
            ----
            DELETE FROM general_params;
            ----
            INSERT INTO general_params ("name", "value") VALUES ('list_report_data_table', 'V_Settings_File_Picklist');
            INSERT INTO general_params ("name", "value") VALUES ('list_report_data_sort_dir', 'DESC');
            INSERT INTO general_params ("name", "value") VALUES ('list_report_helper_multiple_selection', 'no');
            INSERT INTO general_params ("name", "value") VALUES ('list_report_data_sort_col', 'Job Count');
            [changes]
            BEGIN TRANSACTION; UPDATE general_params SET "value" = 'SortKey' WHERE name = 'list_report_data_sort_col'; COMMIT;
            [END]
         */

        $s = "";
        $s .= "\n=============================\n";
        $s .= "[ENTRY]\n";
        $s .= $header."\n";
        $s .= "[restore]\n";
        $s .= $restore;
        $s .= "[changes]\n";
        $s .= $change."\n";
        $s .= "[END]";

        file_put_contents($file, $s, FILE_APPEND);
    }

    /**
     * Dump contents of each table
     * @param string $config_db Config DB name, including .db
     * @param type $tbl_list
     * @return string
     */
    private
    function _get_table_dump_all($config_db, $tbl_list)
    {

        $s = "";
        $tables = $this->config_model->get_table_list();
        foreach ($tables as $t) {
            $s .= "<table class='cfg_tab'>";
            $s .= "<tr><th>";
            $s .= "<div><span>$t</span></div>";
            $s .= "</th></tr>";
            $s .= "<tr><td>";
            $s .= "<a href='javascript:show_hide_block(\"block_$t\")' >Show/Hide</a> &nbsp;";
            $s .= $this->_make_wiki_help_link($t);
            if (in_array($t, $tbl_list)) {
                if ($this->mod_enabled) {
                    $s .= " &nbsp; <a href='".site_url()."config_db/edit_table/$config_db/$t'>Edit</a>";
                    $s .= " &nbsp; <a href=\"javascript:ops('remove_table/$config_db/$t')\">Delete</a>";
                    $s .= "<br>\n";
                }
                $s .= $this->_get_table_dump($config_db, $t);
            } else {
                if ($this->mod_enabled) {
                    $s .= " &nbsp; <a href=\"javascript:ops('create_table/$config_db/$t')\">Create</a>";
                    $s .= "<br>\n";
                } else {
                    $s .= " &nbsp; (not present)";
                    $s .= "<br>\n";
                }
            }
            $s .= "</td></tr>";
            $s .= "</table>";
        }
        $s .= "\n\n";
        return $s;
    }

    /**
     * Get contents of a single table
     * @param string $config_db Config DB name, including .db
     * @param string $table_name Table name
     * @return PDOStatement PDOStatement object, or FALSE on failure.
     */
    private
    function _get_table_contents($config_db, $table_name)
    {
        $dbFilePath = $this->configDBPath.$config_db;
        $dbh = new PDO("sqlite:$dbFilePath");
        $r = $dbh->query("SELECT * FROM $table_name", PDO::FETCH_ASSOC);
        return $r;
    }

    /**
     * Get contents of table as HTML
     * @param string $config_db Config DB name, including .db
     * @param string $table_name Table name
     * @return string
     */
    private
    function _get_table_dump($config_db, $table_name)
    {
        $dbFilePath = $this->configDBPath.$config_db;
        $dbh = new PDO("sqlite:$dbFilePath");
        $i = 0;
        $n = 1;
        $rs = "";
        foreach ($dbh->query("SELECT * FROM $table_name", PDO::FETCH_ASSOC) as $row) {
            if (!$i++) {
                $cols = array_keys($row);
                $n = count($cols);
                // column headers
                $rs .= "<tr>\n";
                foreach ($cols as $c) {
                    $rs .= "<th>$c</th>";
                }
                $rs .= "</tr>\n";
            }
            $rs .= "<tr>\n";
            foreach ($cols as $c) {
                $x = ($row[$c]) ? $row[$c] : '&nbsp;';
                $rs .= "<td>$x</td>";
            }
            $rs .= "</tr>\n";
        }
        $s = "";
        $s .= "<div class='block_content' id='block_$table_name'>\n";
        $s .= "<table class='cfg_tab' >\n";
/*
        // table header
        $s .= "<tr><th colspan='$n''>";
        $s .= "$table_name  &nbsp; ";
        //       $s .= "<a href='".site_url()."config_db/edit_table/$config_db/$table_name'>Edit</a> &nbsp; ";
        //       $s .= "<a href=\"javascript:ops('remove_table/$config_db/$table_name')\">Delete</a> &nbsp; ";
        //       $s .= "<a href=\"javascript:ops('fill_table/$config_db/$table_name')\">Populate</a> &nbsp; ";
        $s .= "</th></tr>\n";
*/
        $s .= "<tr><td colspan='$n'>";
        $s .= $this->config_model->get_table_def($table_name, 'description') . " &nbsp;";
    //  $s .= $this->_make_wiki_help_link($table_name) . " &nbsp;";
        $s .= "</td></tr>\n";
       // table rows
        $s .= $rs;
        // end of table
        $s .= "</table>\n";
        $s .= "</div>\n";
        $s .= "<div style='height:1em;'></div>\n";
        return $s;
    }

    /**
     * Create a table (does not check for an existing table)
     * @param string $config_db Config DB name, including .db
     * @param string $table_name
     * @return type
     */
    function create_table($config_db, $table_name)
    {
        if (!$this->mod_enabled) {
            $this->show_not_allowed($config_db);
            return;
        }
        // FUTURE: Don't do if table already exists
        $sql = $this->config_model->get_table_def($table_name, 'sql');
        $this->_exec_sql($config_db, $sql, $table_name);

        $this->submit_show_db($config_db);
    }

    /**
     * Remove a table
     * @param string $config_db Config DB name, including .db
     * @param string $table_name Table name
     */
    function remove_table($config_db, $table_name)
    {
        if (!$this->mod_enabled) {
            $this->show_not_allowed($config_db);
            return;
        }
        $sql = "drop table $table_name;";
        $this->_exec_sql($config_db, $sql, $table_name);
        $this->submit_show_db($config_db);
    }

    /**
     * Move a range of items in a table
     * @param string $config_db Config DB name, including .db
     * @param string $table_name
     * @param int $range_start_id
     * @param int $range_stop_id
     * @param int $dest_id
     * @return type
     * @category AJAX
     */
    function move_range($config_db, $table_name, $range_start_id, $range_stop_id, $dest_id)
    {
        $this->load->helper(array('config_db'));
        $data_info = $this->_get_config_db_table_data_info($config_db, $table_name);
        $id_col = $data_info->col_names[0];
        if(!in_array($id_col, array('id', 'idx'))) { echo "This table does not allow resequencing"; return; }

        $this->load->helper(array('config_db'));
        echo make_sql_to_move_range_of_items($table_name, $range_start_id, $range_stop_id, $dest_id, $id_col);
    }

    /**
     * Reorder items in a table
     * @param string $config_db Config DB name, including .db
     * @param string $table_name
     * @return type
     * @category AJAX
     */
    function resequence_table($config_db, $table_name)
    {
        $this->load->helper(array('config_db'));
        $data_info = $this->_get_config_db_table_data_info($config_db, $table_name);
        $id_col = $data_info->col_names[0];
        if(!in_array($id_col, array('id', 'idx'))) { echo "This table does not allow resequencing"; return; }

        $roof = '10000';
        $temp_table = "reseq";

        $sql = "-- renumber the $id_col column starting at 1 and removing any gaps\n";
        $sql .= "create table $temp_table ($id_col integer primary key, old_id integer);\n";
        $sql .= "insert into $temp_table (old_id) select $id_col from $table_name;\n";
        $sql .= "update $table_name set $id_col = (select $id_col + $roof from $temp_table where $temp_table.old_id = $table_name.$id_col);\n";
        $sql .= "update $table_name set $id_col = $id_col - $roof;\n";
        $sql .= "drop table $temp_table;\n";
        echo $sql;
    }

    /**
     * Execute the SQL to obtain the results
     * @param string $config_db Config DB name, including .db
     * @param string $table_name Table name
     * @return type
     * @category AJAX
     */
    function exec_sql($config_db, $table_name)
    {
        if (!$this->mod_enabled) {
            $this->show_not_allowed($config_db);
            return;
        }
        $this->load->helper(array('config_db'));
        $sql = $this->input->post('sql_text', '');
        if ($sql) {
            $this->_exec_sql($config_db, $sql, $table_name);
        }
        $data_info = $this->_get_config_db_table_data_info($config_db, $table_name);
        $data['edit_table'] = $this->_get_edit_table_form($config_db, $table_name, $data_info);

        $data['post'] = '';
        $data['posting_message'] = '';
        $this->load->vars($data);
        $this->load->view('config_db/sub_table_edit');
    }

    /**
     * Get suggested INSERT and UPDATE queries to populate the table
     * @param string $config_db Config DB name, including .db
     * @param string $table_name Table name
     * @category AJAX
     */
    function get_suggested_sql($config_db, $table_name)
    {
        $this->load->helper(array('config_db'));

        $name = str_replace('.db', '', $config_db);
        $mode = $this->input->post('mode', '');
        $sql = "";
        switch ($mode) {
            case 'suggest':
                $func_name = "_get_suggested_".$table_name;
                if(method_exists($this, $func_name)) {
                    $sql .= $this->$func_name($config_db);
                } else {
                    $sql .= "No suggestion is available";
                }
                break;
            case 'dump':
                $sql = $this->_get_table_contents_sql($config_db, $table_name);
                break;
        }
        echo $sql;
    }

    /**
     * Get suggested entry commands
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_entry_commands($config_db)
    {
        $sql = "";
        $sql .= "No suggestions for this table";
        return $sql;
    }

    /**
     * Get suggested external sources
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_external_sources($config_db)
    {
        $sql = "";
        $sql .= "No suggestions for this table";
        return $sql;
    }

    /**
     * Get suggested general params
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_general_params($config_db)
    {
        $sql = "";
        $name = str_replace('.db', '', $config_db);
        $obj = $this->_get_standard_names($name);
        $sql .= make_suggested_sql_for_general_params($obj);
        return $sql;
    }

    /**
     * Get suggested detail report commands
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_detail_report_commands($config_db)
    {
        $sql = "";
        $sql .= "Working on it";
        return $sql;
    }

    /**
     * Get suggested primary filter choosers
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_primary_filter_choosers($config_db)
    {
        $sql = "";
        $table_name = 'primary_filter_choosers';
        $source_table_name = 'list_report_primary_filter';
        $sql = "DELETE FROM $table_name;\n----\n";
        $r = $this->_get_table_contents($config_db, $source_table_name);
        foreach ($r as $row) {
            $n = $row['name'];
            $sql .= "INSERT INTO $table_name (\"field\", \"type\", \"PickListName\", \"Target\", \"XRef\", \"Delimiter\") VALUES ('$n', 'picker.replace', 'PickListName', '', '', ',');\n";
            $sql .= "INSERT INTO $table_name (\"field\", \"type\", \"PickListName\", \"Target\", \"XRef\", \"Delimiter\") VALUES ('$n', 'list-report.helper', '', 'Target', '', ',');\n";
            $sql .= "----\n";
        }
        return $sql;
    }

    /**
     * Get suggested form field choosers
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_form_field_choosers($config_db)
    {
        $sql = "";
        $table_name = 'form_field_choosers';
        $source_table_name = 'form_fields';
        $sql = "DELETE FROM $table_name;\n----\n";
        $r = $this->_get_table_contents($config_db, $source_table_name);
        foreach ($r as $row) {
            $n = $row['name'];
            $sql .= "INSERT INTO $table_name (\"field\", \"type\", \"PickListName\", \"Target\", \"XRef\", \"Delimiter\", \"Label\") VALUES ('$n', 'picker.replace', 'PickListName', '', '', ',', '');\n";
            $sql .= "INSERT INTO $table_name (\"field\", \"type\", \"PickListName\", \"Target\", \"XRef\", \"Delimiter\", \"Label\") VALUES ('$n', 'list-report.helper', '', 'Target', '', ',', '');\n";
            $sql .= "----\n";
        }
        return $sql;
    }

    /**
     * Get suggested form field options
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_form_field_options($config_db)
    {
        $sql = "";
        $table_name = 'form_field_options';
        $source_table_name = 'form_fields';
        $sql = "DELETE FROM $table_name;\n----\n";
        $r = $this->_get_table_contents($config_db, $source_table_name);
        foreach ($r as $row) {
            $n = $row['name'];
            $sql .= "INSERT INTO $table_name (\"field\", \"type\", \"parameter\") VALUES ('$n', 'default_function', 'GetUser()');\n";
            $sql .= "----\n";
        }
        return $sql;
    }

    /**
     * Get table contents as SQL INSERT statements
     * @param string $config_db Config DB name, including .db
     * @param string $table_name Table name
     * @return string
     */
    private
    function _get_table_contents_sql($config_db, $table_name)
    {
        $sql = "";
        $data_info = $this->_get_config_db_table_data_info($config_db, $table_name);

        if ($data_info->num_cols > 2) {
            array_shift($data_info->col_names);
        }

        $cfr = array();
        foreach($data_info->col_names as $c) {
            $cfr[] = '"'.$c.'"';
        }

        $r = $this->_get_table_contents($config_db, $table_name);
        if($r) {
            $sql .= "-- $config_db;\n----\n";
            $sql .= "DELETE FROM $table_name;\n----\n";

			if ($config_db === 'master_authorization.db') {
	            $sql .= "UPDATE sqlite_sequence set seq=0 WHERE name = '$table_name';\n----\n";
        	}
        	
            foreach ($r as $row) {
                $vfr = array();
                foreach ($data_info->col_names as $col) {
                    $vfr[$col] = "'".$row[$col]."'";
                }
                $sql .= "INSERT INTO $table_name (";
                $sql .= implode(', ', $cfr);
                $sql .= ") VALUES (";
                $sql .= implode(', ', $vfr);
                $sql .= ");\n";
            }
        }
        return $sql;
    }

    /**
     * Display an editing page for the given table in the given config db
     * @param string $config_db Config DB name, including .db
     * @param string $table_name Table name
     * @return type
     */
    function edit_table($config_db, $table_name)
    {
        if (!$this->mod_enabled) {
            $this->show_not_allowed($config_db);
            return;
        }
        $this->load->helper(array('config_db'));
        $data['title'] = "Config DB Table Edit";
        $data['heading'] = "Edit $table_name in $config_db";
        $data['config_db'] = $config_db;
        $data['table_name'] = $table_name;
        $data['post'] = '';
        $data['sql_text'] = '';

        // get data rows from config table
        $data_info = $this->_get_config_db_table_data_info($config_db, $table_name);
        $data['edit_table'] = $this->_get_edit_table_form($config_db, $table_name, $data_info);
 //       $data['tooltip_events'] = $this->_get_edit_table_form_tooltips($table_name, $data_info);

        $data['posting_message'] = '';
        $this->load->vars($data);
        $this->load->view('config_db/table_edit');
    }

    /**
     * Same as edit_table, except change table then load view 'sub_table_edit'
     * @param string $config_db Config DB name, including .db
     * @param string $table_name
     * @return type
     * @category AJAX
     */
    function submit_edit_table($config_db, $table_name)
    {
        if (!$this->mod_enabled) {
            $this->show_not_allowed($config_db);
            return;
        }
        $this->load->helper(array('config_db'));

        // show _POST (debug)
        $p = '';
        foreach ($_POST as $k=>$v) {
            $p .= "$k:'$v', ";
        }
        $data['post'] = '';

        // get data rows from config table
        $data_info = $this->_get_config_db_table_data_info($config_db, $table_name);

        $mode = '';
        if (array_key_exists('mode', $_POST)) {
            $mode = $_POST['mode'];
        }
        $s = "";
        if ($mode) {
            $key = $data_info->col_names[0];
            if ($data_info->num_cols > 2) {
                array_shift($data_info->col_names);
            }

            $ur = array();
            $ifr = array();
            $vfr = array();
            foreach ($data_info->col_names as $c) {
                if (array_key_exists($c, $_POST)) {
                    $v = $_POST[$c];
                    if ($c != $key) {
                        $ur[] = "\"$c\" = '$v'";
                    }
                    $ifr[] = "\"$c\"";
                    $vfr[] = "'$v'";
                }
            }
            switch ($mode) {
                case 'accept':
                    $s .= "UPDATE $table_name SET ";
                    $s .= implode(', ', $ur) . " ";
                    $s .= "WHERE $key = '$_POST[$key]';";
                    break;
                case 'delete':
                    $s .= "DELETE FROM $table_name WHERE \"$key\" = '$_POST[$key]';";
                    break;
                case 'add':
                    $s .= "INSERT INTO $table_name (";
                    $s .= implode(', ', $ifr);
                    $s .= ") VALUES (";
                    $s .= implode(', ', $vfr);
                    $s .= ");";
                    break;
            }
        }
        if ($s) {
            $this->_exec_sql($config_db, $s, $table_name);
        }

        $data_info_updated = $this->_get_config_db_table_data_info($config_db, $table_name);
        $data['edit_table'] = $this->_get_edit_table_form($config_db, $table_name, $data_info_updated);
//        $data['tooltip_events'] = $this->_get_edit_table_form_tooltips($table_name, $data_info);

        $data['posting_message'] = $s;
        $this->load->vars($data);
        $this->load->view('config_db/sub_table_edit');
    }

    /**
     * Get information for the editing for the given table in the given config db
     * @param string $config_db Config DB name, including .db
     * @param string $table_name
     * @return \stdClass
     */
    private
    function _get_config_db_table_data_info($config_db, $table_name)
    {
        $dbFilePath = $this->configDBPath.$config_db;

        $dbh = new PDO("sqlite:$dbFilePath");

        $col_names = array();
        foreach ($dbh->query("PRAGMA table_info({$table_name});", PDO::FETCH_ASSOC) as $row) {
            $col_names[] = $row['name'];
        }
        $num_cols = count($col_names);

        $data_rows = array();
        $r = $dbh->query("SELECT * FROM $table_name", PDO::FETCH_ASSOC);
        if($r) {
            foreach ($r as $row) {
                $data_rows[] = $row;
            }
        }

        // max width for each col
        $col_widths = array();
        foreach ($col_names as $c) {
            $col_widths[$c] = strlen($c);
        }
        foreach ($data_rows as $row) {
            foreach ($col_names as $c) {
                $x = strlen($row[$c]);
//              $x = ceil($x * 1.2); // pad it out
                if ($x > $col_widths[$c]) {
                    $col_widths[$c] = $x;
                }
            }
        }
        $data_obj = new stdClass ();
        $data_obj->data_rows = $data_rows;
        $data_obj->num_cols = $num_cols;
        $data_obj->col_names = $col_names;
        $data_obj->col_widths = $col_widths;
        $data_obj->exclude_first_col = (count($col_names) > 2);
        return $data_obj;
    }

    /**
     * Return SQL to create appropriate entries for the sproc args table
     * for the given config database
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_sproc_args($config_db)
    {
        $sqla = "";
        $name = str_replace('.db', '', $config_db);

        $db_group = 'default';
        $gen_parms = $this->_get_general_params($config_db, $db_group);
        if (!$gen_parms) {
            echo "Could not get general params";
            return "Problem";
        }
        // for each parameter in general_params that ends in '_sproc'
        // get arguments from main database and convert to sql
        $my_db = $this->load->database($db_group, TRUE);
        foreach($gen_parms as $p => $v) {
            if(!(FALSE === strpos($p, '_sproc'))) {
                $sa = $this->_get_sproc_arg_defs_from_main_db($my_db, $v);
                $sqla .= $this->_get_sproc_arg_sql($sa, $v);
            }
        }

        return $sqla;
    }

    /**
     * Return SQL to create appropriate entries for the form fields table
     * for the given config database
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_form_fields($config_db)
    {
        $sqlf = "";
        $name = str_replace('.db', '', $config_db);

        $db_group = 'default';
        $gen_parms = $this->_get_general_params($config_db, $db_group);
        if (!$gen_parms) {
            echo "Problem";
            return;
        }

        $mainSproc = (array_key_exists('entry_sproc', $gen_parms)) ? $gen_parms['entry_sproc'] : '';
        $mainSproc = (array_key_exists('list_report_sproc', $gen_parms)) ? $gen_parms['list_report_sproc'] : $mainSproc;

        if ($mainSproc) {
            $my_db = $this->load->database($db_group, TRUE);
            $sproc = $mainSproc;
            $sa = $this->_get_sproc_arg_defs_from_main_db($my_db, $sproc);
            $sqlf = $this->_get_form_field_sql($sa);
        }
        return $sqlf;
    }

    /**
     * Show results in a table
     * @param string $config_db Config DB name
     * @param string $table_name
     * @param type $data_obj
     * @return string
     */
    private
    function _get_edit_table_form($config_db, $table_name, $data_obj)
    {
        $s = "";

        if ($data_obj->num_cols > 0)
            $max_width = floor(400/$data_obj->num_cols);
        else
            $max_width = 400;

        $accept_img = "<img src='".base_url()."images/accept.png' border='0'  alt='accept' />";
        $delete_img = "<img src='".base_url()."images/delete.png' border='0'  alt='delete' />";
        $add_img = "<img src='".base_url()."images/add.png' border='0' alt='add' />";
        $attip = "Update this row in the table";
        $dttip = "Delete this row from the table";

        $s .= "<form id='edit_form' name='edit_form' action='post'>\n";

        // outer table
        $s .= "<table class='cfg_tab' >\n";
        $s .= "<tr><th>";
        $s .= "<span>$table_name</span>";
        $s .= "</td></tr>";

        // inner table
        $s .= "<tr><td>";
        $s .= $this->_make_wiki_help_link($table_name);

        $s .= "&nbsp;&nbsp;" . $this->_make_page_family_contents_link($config_db);

        $s .= "<table class='cfg_tab' >\n";
        // table header
        $nc = $data_obj->num_cols + 2;

        $s .= "<tr>";
        $s .= "<td colspan='$nc'>";
        $s .= $this->config_model->get_table_def($table_name, 'description') . " &nbsp;";
        $s .= "</td>";
        $s .= "</tr>\n";

        // column headers
        $s .= make_edit_table_header($data_obj->col_names, 'th');
/*
        $hdr = "<tr>\n";
        foreach ($data_obj->col_names as $col_name) {
            $hdr .= "<th>$col_name</th>";
        }
        $hdr .= "<th>&nbsp;</th>";
        $hdr .= "<th>&nbsp;</th>";
        $hdr .= "</tr>\n";
        $s .= $hdr;
*/
        /**
         * Number of data rows
         */
        $n = 0;

        /**
         * Array of column widths
         */
        $col_widths = $data_obj->col_widths;

        foreach ($data_obj->data_rows as $row) {
            $fid = $n++;
            $s .= "\n<tr>\n";
            foreach ($data_obj->col_names as $col_name) {
                $col_width = $col_widths[$col_name];
                $col_val = ($row[$col_name]) ? $row[$col_name] : ''; ///grk &nbsp;

                if ($col_name == $data_obj->col_names[0]) {
                    // Column name
                    $a = "<a href='javascript:set_id(\"$col_val\")' class='cfg_id_link'>$col_val</a>";
                    $s .= "<td><input name='$col_name' size='$col_width' value='$col_val' type='hidden' />$a</td>";
                } else {
                    // Column value

                    // Replace single quotes with the character code for a single quote, &#39;
                    // Listing the character code twice to allow for saving changes
                    $col_val = str_replace("'", "&#39;&#39;", $col_val);

                    if($col_width < $max_width) {
                        $s .= "<td><input name='$col_name' size='$col_width' value='$col_val' /></td>";
                    } else {
                        $num_rows = floor(strlen($col_val)/$max_width);
                        $num_rows = ($num_rows == 0) ? 1 : $num_rows;
                        $num_rows = ($num_rows > 5) ? 5 : $num_rows;
                        $s .= "<td><textarea name='$col_name' rows='$num_rows' cols='$max_width'>$col_val</textarea></td>";
                    }
                }
            }
            $accept_action = "ops(\"$fid\", \"accept\")";
            $delete_action = "ops(\"$fid\", \"delete\")";
            $s .= "<td><a href='javascript:$accept_action' title='$attip'>$accept_img</a></td>";
            $s .= "<td><a href='javascript:$delete_action' title='$dttip'>$delete_img</a></td>";
            $s .= "\n</tr>\n";
        }

        $s .= "<tr><td colspan=\"$nc\">New Row:</td></tr>\n";
        $s .= make_edit_table_header($data_obj->col_names, 'td');

        // new entry row
        $fid = "frm_add";
        $s .= "<tr>\n";
        foreach ($data_obj->col_names as $col_name) {
            if ($col_name == $data_obj->col_names[0] && $data_obj->exclude_first_col) {
                $s .= "<td>&nbsp;</td>";
            } else {
                $s .= "<td>".$this->_get_edit_table_entry_field($config_db, $table_name, $col_name, $col_widths[$col_name], $max_width)."</td>";
            }
        }
        $add_action = "ops($n, \"add\")";
        $tooltip = "Add contents of row to table";
        $s .= "<td><a href='javascript:$add_action' title='$tooltip'>$add_img</a></td>";
        $s .= "<td>&nbsp;</td>\n";
        $s .= "</tr>\n";

        $s .= "</table>\n";
        $s .= "</td></tr>";
        $s .= "</table>\n";
        // dummy row in form to insure that JS always sees field as part of array
        // if table is empty
        foreach ($data_obj->col_names as $col_name) {
            $s .= "<input name='$col_name' type='hidden' value='na' />";
        }
        $s .= "</form>\n";
        return $s;
    }

    /**
     * Get html for editing a table entry
     * @param string $config_db Config DB name
     * @param string $table_name Table name
     * @param string $col_name
     * @param integer $col_width
     * @param integer $max_width
     * @return string
     */
    private
    function _get_edit_table_entry_field($config_db, $table_name, $col_name, $col_width, $max_width)
    {
        $s = "";
        $sdv = "";
        $obj = $this->config_model->get_edit_table_entry_field_def($table_name, $col_name);
        if($obj->type != "empty") {
            $sx = "";
            switch($obj->type) {
                case "default_value":
                    $sdv = $obj->value;
                    break;
                case "literal_list":
                    foreach($obj->list as $sel) {
                        $sx .= "<option>$sel</option>";
                    }
                    break;
                case "dms_view_cols":
                    $db_group = 'default';
                    $gen_parms = $this->_get_general_params($config_db, $db_group);
                    $pn = $obj->view;
                    $table = (array_key_exists($pn, $gen_parms)) ? $gen_parms[$pn] : '';
                    if($table) {
                        $my_db = $this->load->database($db_group, TRUE);
                        $fields = $my_db->field_data($table);
                        foreach ($fields as $f) {
                            $sx .= "<option>$f->name</option>";
                        }
                    }
                    break;
                case "config_col_values":
                    $cn = $obj->column;
                    $source_table_name = $obj->table;
                    $r = $this->_get_table_contents($config_db, $source_table_name);
                    foreach ($r as $row) {
                        $n = $row[$cn];
                        $sx .= "<option>$n</option>";
                    }
                    break;
            }
            if($sx) {
                $s .= "<select name='$col_name'>$sx</select>";
            }
        }

        if(!$s) {
            if($col_width < $max_width) {
                $s .= "<input name='$col_name' size='$col_width' value='$sdv'/>";
            } else {
                $num_rows = ceil($col_width/$max_width);
                $num_rows = ($num_rows == 0) ? 1 : $num_rows;
                $num_rows = ($num_rows > 5) ? 5 : $num_rows;
                $s .= "<textarea name='$col_name' rows='$num_rows' cols='$max_width'>$sdv</textarea>";
            }
        }
        return $s;
    }

    /**
     * Output sql to make hotlinks for all columns in the list report view defined in the given config db
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_list_report_hotlinks($config_db)
    {
        $name = str_replace('.db', '', $config_db);

        $s = "";
        $db_group = 'default';
        $gen_parms = $this->_get_general_params($config_db, $db_group);
        if (!$gen_parms) {
            $s .= "Problem";
            return;
        }

        $pn = 'list_report_data_table';
        $table = (array_key_exists($pn, $gen_parms)) ? $gen_parms[$pn] : '';
        if (!$table) {
            $s .= "List report view not defined in general_params table in config db '$config_db'.";
            return;
        }

        $my_db = $this->load->database($db_group, TRUE);
        $fields = $my_db->field_data($table);

        $s .= "delete from list_report_hotlinks;\n";
        $s .= "-----------\n";
        $s .= "insert into list_report_hotlinks (\"name\", \"LinkType\", \"WhichArg\", \"Target\", \"Options\") values ('ID', 'invoke_entity', 'value', '$name/show/', '');\n";
        $s .= "-----------\n";
        $pf = "insert into list_report_hotlinks (\"name\", \"LinkType\", \"WhichArg\", \"Target\", \"Options\") values (";
        foreach ($fields as $f) {
            $name = $f->name;
            $x = str_replace(' ', '_', strtolower($f->name));
            $target = $x."/show";
            $type = 'invoke_entity';
            $s .= "{$pf}'{$name}', '{$type}', '{$name}', '{$target}', '');\n";
        }
        return $s;
    }

    /**
     * Output sql to make hotlinks for all columns in the detail report view defined in the given config db
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_detail_report_hotlinks($config_db)
    {
        $name = str_replace('.db', '', $config_db);

        $s = "";
        $db_group = 'default';
        $gen_parms = $this->_get_general_params($config_db, $db_group);
        if (!$gen_parms) {
            $s .= "Problem";
            return;
        }

        $pn = 'detail_report_data_table';
        $table = (array_key_exists($pn, $gen_parms)) ? $gen_parms[$pn] : '';
        if (!$table) {
            $s .= "Detail report view not defined in general_params table in config db '$config_db'.";
            return;
        }

        $my_db = $this->load->database($db_group, TRUE);

        $fields = $my_db->field_data($table);

        $s .= "delete from detail_report_hotlinks;\n";
        $s .= "-----------\n";
        $pf = "insert into detail_report_hotlinks (\"name\", \"LinkType\", \"WhichArg\", \"Target\", \"Placement\", \"id\") values (";
        foreach ($fields as $f) {
            $name = $f->name;
            $x = str_replace(' ', '_', strtolower($f->name));
            $id = "dl_".$x;
            $target = $x."/show";

            $type = 'detail-report';
            $s .= "{$pf}'{$name}', '{$type}', '{$name}', '{$target}', 'labelCol', '{$id}');\n";
        }
        return $s;
    }

    /**
     * Output sql to make primary filters for all columns in the list report view in the given config db
     * @param string $config_db Config DB name, including .db
     * @return string
     */
    private
    function _get_suggested_list_report_primary_filter($config_db)
    {
        $name = str_replace('.db', '', $config_db);

        $s = '';
        $db_group = 'default';
        $gen_parms = $this->_get_general_params($config_db, $db_group);
        if (!$gen_parms) {
            $s .= "Problem";
            return;
        }

        $pn = 'list_report_data_table';
        $table = (array_key_exists($pn, $gen_parms)) ? $gen_parms[$pn] : '';
        if (!$table) {
            $s .= "List report view not defined in general_params table in config db '$config_db'.";
            return;
        }

        $my_db = $this->load->database($db_group, TRUE);
        $fields = $my_db->field_data($table);

        $s .= "delete from list_report_primary_filter;\n";
        $s .= "-----------\n";
        $pf = "insert into list_report_primary_filter ( \"name\", \"label\", \"size\", \"value\", \"col\", \"cmp\", \"type\", \"maxlength\", \"rows\", \"cols\") values (";
        foreach ($fields as $f) {
            $name = "pf_".str_replace(' ', '_', strtolower($f->name));
            $label = $f->name;
            $size = '20';
            $value = '';
            $col = $f->name;
            $cmp = 'ContainsText';
            if ($f->type == 'int') {
                $cmp = 'Equals';
            } elseif ($f->type == 'datetime') {
                $cmp = 'LaterThan';
            }
            $type = 'text';
            $ml = ($f->max_length < 20) ? '20' : $f->max_length;
            $rows = '';
            $cols = '';
            $s .= "{$pf}'{$name}', '{$label}', '{$size}', '{$value}', '{$col}', '{$cmp}', '{$type}', '{$ml}', '{$rows}', '{$cols}');\n";
        }
        return $s;
    }

    /**
     * Get the list of tables in the database
     * @param string $config_db Config DB name, including .db
     * @param string $table_filter
     * @return string[]
     */
    private
    function _get_db_table_list($config_db, $table_filter='')
    {
        $s = "";
        $table_list = array();
        $dbFilePath = $this->configDBPath.$config_db;

        $dbh = new PDO("sqlite:$dbFilePath");
        if (!$dbh) {
            $s .= 'Could not connect to config database at '.$dbFilePath;
            return null;
        }
        foreach ($dbh->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'", PDO::FETCH_ASSOC) as $row) {
            $table_list[] = $row['tbl_name'];
        }

        // filter table names?
        if($table_filter) {
            $tx = array();
            foreach($table_list as $tn) {
                if(false !== strpos($tn, $table_filter)) {
                    $tx[] = $tn;
                }
            }
            $table_list = $tx;
        }
       return $table_list;
    }

    /**
     * Get the general_params table contents
     * @param string $config_db Config DB name, including .db
     * @param string $db_group
     * @return string[]
     */
    private
    function _get_general_params($config_db, &$db_group)
    {
        $s = "";
        $gen_parms = array();
        $dbFilePath = $this->configDBPath.$config_db;

        $dbh = new PDO("sqlite:$dbFilePath");
        if (!$dbh) {
            $s .= 'Could not connect to config database at '.$dbFilePath;
            return null;
        }
        foreach ($dbh->query("SELECT * FROM general_params", PDO::FETCH_ASSOC) as $row) {
            $gen_parms[$row['name']] = $row['value'];
            if ($row['name'] == 'my_db_group') {
                $db_group = $row['value'];
            }
        }
        return $gen_parms;
    }

    /**
     * Read the definitions for the arguments for the given
     * stored procedure in the given database, do some conversions
     * from the raw format of the INFORMATION_SCHEMA, and return in an array
     * @param type $dbObj
     * @param type $sproc
     * @return type Array of stored procedure argument info
     */
    private
    function _get_sproc_arg_defs_from_main_db($dbObj, $sproc)
    {
        $sa = array();
        $sql = "SELECT * FROM INFORMATION_SCHEMA.PARAMETERS WHERE SPECIFIC_NAME = '".$sproc."'";
        $result = $dbObj->query($sql);
        if (!$result) {
            $str = "Couldn't get values from database.";
        } else {
            foreach ($result->result_array() as $row) {
                $arg = str_replace('@', '', $row['PARAMETER_NAME']);
                $typ = $row['DATA_TYPE'];
                $dir = ($row['PARAMETER_MODE'] == 'INOUT') ? 'output' : 'input';
                $siz = $row['CHARACTER_MAXIMUM_LENGTH'];
                if ($typ == 'varchar' && $siz == '-1')
                    $siz = '2147483647';
                $seq = $row['ORDINAL_POSITION'];
                $fld = $arg;
                switch ($fld) {
                    case 'message':
                    case 'mode':
                    case 'callingUser':
                        $fld = '<local>';
                        break;
                }
                $sa[] = array('f'=>$fld, 'a'=>$arg, 't'=>$typ, 'd'=>$dir, 's'=>$siz);
                //              echo "'f' => $fld, 'a' => $arg, 't' => $typ, 'd' => $dir, 's' => $siz \n";
            }
        }
        return $sa;
    }

    /**
     * Return SQL to create the appropriate entries in the sproc_args table
     * from given sproc args definition array
     * @param type $sa Array of stored procedure argument info
     * @param string $sproc Stored procedure name
     * @return type
     */
    private
    function _get_sproc_arg_sql($sa, $sproc)
    {
        $table = 'sproc_args';
        $sql = "DELETE FROM $table WHERE procedure = '".$sproc."';\n";
        $pf = "INSERT INTO $table (\"field\", \"name\", \"type\", \"dir\", \"size\", \"procedure\") VALUES (";
        foreach ($sa as $s) {
            $sql .= "{$pf}'{$s['f']}', '{$s['a']}', '{$s['t']}', '{$s['d']}', '{$s['s']}', '{$sproc}');\n";
        }
        return $sql;
    }

    /**
     * Return SQL to create appropriate entries in the form field table
     * from given sproc args definition array
     * @param type $sa Array of stored procedure argument info
     * @return type
     */
    private
    function _get_form_field_sql($sa)
    {
        $table = 'form_fields';
        $sql = "DELETE FROM $table;\n";
        $pf = "INSERT INTO $table (\"name\", \"label\", \"type\", \"size\", \"maxlength\", \"rows\", \"cols\", \"default\", \"rules\") VALUES (";
        foreach ($sa as $s) {
            if ($s['f'] == '<local>')
                continue;
            $ft = "text";
            $ml = ($s['s'] == '') ? '12' : $s['s'];
            $sz = ($ml > 50) ? '50' : $ml;
            $rw = '';
            $cl = '';
            $lb = str_replace('_', '', $s['f']);
            $lb = ucfirst(preg_Replace('/([A-Z][A-Z]*)/', ' $1', $lb));
            $rls = "trim|max_length[$ml]";
            if ($ml > 130) { // big fields get text areas
                $ft = "area";
                $sz = '';
                $ml = '';
                $rw = '4';
                $cl = '70';
            }
            if ($s['f'] == 'ID') {
                $ft = "non-edit";
                $sz = '';
                $ml = '';
                $rw = '';
                $cl = '';
                $rls = 'trim';
            }
            $sql .= "{$pf}'{$s['f']}', '{$lb}', '{$ft}', '{$sz}', '{$ml}', '{$rw}', '{$cl}', '', '{$rls}');\n";
        }
        return $sql;
    }

    /**
     * Generate SQL to create basic database objects for a page family from the given table.
     * This will include the three views and the AddUpdate sproc
     */
    function code_for_family_sql()
    {
        $this->load->helper(array('config_db'));
        $config_db = $this->uri->segment(3);

        $db_group = 'default';
        $gen_parms = $this->_get_general_params($config_db, $db_group);

        $my_db = $this->load->database($db_group, TRUE);

        header("Content-type: text/plain");

        echo make_family_sql($my_db, $gen_parms);
    }

    /**
     * Display example C# code for calling the stored procedure associated with this config DB
     * @param type $db_group
     * @param type $sproc
     */
    function code_for_csharp($db_group, $sproc)
    {
        $this->load->helper(array('config_db'));

        $db_group = $this->uri->segment(3);
        $sproc = $this->uri->segment(4);

        $my_db = $this->load->database($db_group, TRUE);
        $sa = $this->_get_sproc_arg_defs_from_main_db($my_db, $sproc);

        header("Content-type: text/plain");
        echo make_csharp($my_db, $sa);
    }

    /**
     * Check whether the controller exists, e.g. controllers/Analysis_job.php
     * @param string $config_db Config DB name, including .db
     * @param string $page_fam_tag
     * @param string $file_path
     * @return boolean
     */
    private
    function _controller_exists($config_db, &$page_fam_tag, &$file_path)
    {
        // set up file names

        // Assure that the page family name is all lowercase
        $page_fam_tag = strtolower(str_replace('.db', '', $config_db));
        $dir = "application/controllers/";

        // The controller filename must start with a capital letter then be all lowercase
        $file_path = $dir . ucfirst($page_fam_tag) . '.php';

        return file_exists($file_path);
    }

    /**
     * Create the controller (if it doesn't yet exist)
     * @param string $config_db Config DB name, including .db
     * @param string $title
     * @return type
     */
    function make_controller($config_db, $title)
    {
        if (!$this->mod_enabled) {
            $this->show_not_allowed($config_db);
            return;
        }

        $this->load->helper(array('config_db'));

        $page_fam_tag = "";
        $file_path = "";
        if($this->_controller_exists($config_db, $page_fam_tag, $file_path)) {
            echo "Controller file '$file_path' not created because it exists";
            return;
        }

        $ignore = '';
        $data_info = $this->_get_general_params($config_db, $ignore);

        // create controller file contents
        $s = make_controller_code($config_db, $page_fam_tag, $data_info, $title);

        // write controller file
        file_put_contents($file_path, $s);
        header("Content-type: text/plain");
        echo "Controller file was created as '$file_path'\n\n";
        echo $s;
    }

    /**
     * Obtain standard view, stored procedure, and table names
     * @param type $page_family_tag
     * @return \stdClass
     */
    private
    function _get_standard_names($page_family_tag)
    {
        $baseName = ucwords(str_replace("_", " ", $page_family_tag));

        $baseViewName = str_replace(" ", "_", $baseName);
        $baseProcName = str_replace(" ", "", $baseName);

        $obj = new stdClass ();
        $obj->lrn = "V_".$baseViewName."_List_Report";
        $obj->drn = "V_".$baseViewName."_Detail_Report";
        $obj->ern = "V_".$baseViewName."_Entry";
        $obj->spn = "AddUpdate".$baseProcName;
        $obj->upn = "Update".$baseProcName;
        $obj->tbl = 'T_'.$baseViewName;
        return $obj;
    }

    /**
     * Retury array of config files in the config folder
     * @param string $file_filter
     * @return type
     */
    private
    function _get_config_db_file_list($file_filter = '')
    {
        $config_files = array();
        if ($handle = opendir($this->configDBPath)) {
            while (false !== ($file = readdir($handle))) {
                if(preg_match($file_filter, $file)) {
                    $config_files[] = $file;
                }
            }
            closedir($handle);
        }
        return $config_files;
    }

    /**
     * Return array of table names for all the config dbs where the config db
     * file names satisfy the $file_filter and the table names satisfy the $table_filter
     * @param type $file_filter
     * @param type $table_filter
     * @return type
     */
    private
    function _get_filtered_config_table_name_list($file_filter, $table_filter)
    {
        // get list of config files from config folder
        $config_files = $this->_get_config_db_file_list($file_filter);
        asort($config_files);

        // get list of tables for each config db
        $config_db_table_name_list = array();
        foreach($config_files as $config_db) {
            $tbl_list = $this->_get_db_table_list($config_db, $table_filter);
            asort($tbl_list);
            $config_db_table_name_list[$config_db] = $tbl_list;
        }
        return $config_db_table_name_list;
    }


    /**
     * Return list of tables in all the config dbs where the config db file names
     * satisfy the $file_filter and the table names satisfy the $table_filter
     * @param type $file_filter
     * @param type $table_filter
     * @return type
     */
    private
    function _get_filtered_config_table_list($file_filter, $table_filter)
    {
        // get list of config files from config folder
        $config_files = $this->_get_config_db_file_list($file_filter);
        asort($config_files);

        // get list of tables for each config db
        $config_db_table_list = array();
        foreach($config_files as $config_db) {
            $tbl_list = $this->_get_db_table_list($config_db, $table_filter);
            asort($tbl_list);
            // get contents of each table
            $tb = array();
            foreach($tbl_list as $table_name) {
                $tb[$table_name] = $this->_get_table_contents($config_db, $table_name);
            }
            $config_db_table_list[$config_db] = $tb;
        }
        return $config_db_table_list;
    }

    /**
     * Search contents of Config DBs
     * Example URLs:
     * http://dms2.pnl.gov/config_db/search/dataset.db/list_report_hotlink
     * http://dms2.pnl.gov/config_db/search/dataset/list_report_hotlink
     * http://dms2.pnl.gov/config_db/search/experiment/_
     */
    function search()
    {
        $this->load->helper(array('config_db'));

        // set up name filters
        $raw_filter = $this->uri->segment(3, ".db");
        $file_filter = "/".$raw_filter."/";
        $table_filter = $this->uri->segment(4, "");

        $data['raw_filter'] = $raw_filter;
        $data['file_filter'] = $file_filter;
        $data['table_filter'] = $table_filter;

        // different output format
        $display_format = $this->uri->segment(5, 'table_dump');
        $data['display_format'] = $display_format;
        $display_mode = $this->uri->segment(6, '');
        $data['display_mode'] = $display_mode;

        $data['title'] = "Search config files";
        $data['heading'] = "Search Contents of Config DBs";

       $config_db_table_list = $this->_get_filtered_config_table_list($file_filter, $table_filter);

       if($display_format == 'text') {
            make_table_dump_text($config_db_table_list, $display_mode);
       } else {
           $data['config_db_table_list'] = $config_db_table_list;
           $this->load->vars($data);
           $this->load->view('config_db/dump_db');
       }
   }

    /**
     * View page family database list
     * http://dms2.pnl.gov/config_db/page_families
     */
    function page_families()
    {
        $this->load->helper(array('config_db'));
        $data['title'] = "Page Family Directory";
        $data['heading'] =  $data['title'];

        $file_filter = "/db/";

        // get list of tables in all config dbs
        $config_db_table_name_list = $this->_get_filtered_config_table_name_list($file_filter, '');

        // get contents for general_params table for all config dbs
        $table_filter = "general_params";
        $config_db_table_list = $this->_get_filtered_config_table_list($file_filter, $table_filter);

        // get crosstab data
        $data['contents'] = make_general_params_dump($config_db_table_list, $config_db_table_name_list);

        $this->load->vars($data);
        $this->load->view('config_db/main');
    }

    /**
     * View support database list
     * http://dms2.pnl.gov/config_db/support_config_db_list
     */
    function support_config_db_list()
    {
        $this->load->helper(array('config_db'));
        $data['title'] = "Support Config DB Directory";
        $data['heading'] =  $data['title'];

        $s = "";
        $s .= "<table class='cfg_tab'>\n";
        $s .= "<tr><th>Config DB System Parameters</th></tr>";
        $s .= "<tr><td><a href='".site_url()."config_db/edit_table/master_config_db.db/table_def_description'>Edit</a>  table_def_description config db</td></tr>";
        $s .= "<tr><td><a href='".site_url()."config_db/edit_table/master_config_db.db/table_def_sql'>Edit</a> table_def_sql config db</td></tr>";
        $s .= "<tr><td><a href='".site_url()."config_db/edit_table/master_config_db.db/table_edit_col_defs'>Edit</a> table_edit_col_defs  config db</td></tr>";
        $s .= "<tr><th>Home Page Menu Sections</th></tr>";
        $s .= "<tr><td><a href='".site_url()."config_db/edit_table/dms_menu.db/home_menu_sections'>Edit</a> menu sections</td></tr>";
        $s .= "<tr><td><a href='".site_url()."config_db/edit_table/dms_menu.db/home_menu_items'>Edit</a> menu items</td></tr>";
        $s .= "<tr><th>DMS Menus</th></tr>";
        $s .= "<tr><td><a href='".site_url()."config_db/edit_table/dms_menu.db/menu_def'>Edit</a> side menu items</td></tr>";
        $s .= "<tr><td><a href='".site_url()."config_db/edit_table/dms_menu.db/nav_def'>Edit</a> nav bar items</td></tr>";
        $s .= "<tr><th>Admin Page Menu Sections</th></tr>";
        $s .= "<tr><td><a href='".site_url()."config_db/edit_table/dms_admin_menu.db/home_menu_sections'>Edit</a> menu sections</td></tr>";
        $s .= "<tr><td><a href='".site_url()."config_db/edit_table/dms_admin_menu.db/home_menu_items'>Edit</a> menu items</td></tr>";
        $s .= "<tr><th>Drop-down Choosers (Pick Lists)</th></tr>";
        $s .= "<tr><td><a href='".site_url()."chooser/get_chooser_list'>Display</a> list of all drop-down style choosers (e.g. datasetTypePickList)</td></tr>";
        $s .= "<tr><td><a href='".site_url()."config_db/edit_table/dms_chooser.db/chooser_definitions'>Edit</a> drop-down chooser definitions config db</td></tr>";
        $s .= "<tr><th>Definitions for Restricted Actions</th></tr>";
        $s .= "<tr><td><a href='".site_url()."gen/auth'>Display</a> list of restricted actions</td></tr>";
        $s .= "<tr><td><a href='".site_url()."config_db/edit_table/master_authorization.db/restricted_actions'>Edit</a>  master_authorization restricted actions config db</td></tr>";
        $s .= "</table>\n";

        $data['contents'] = $s;

        $this->load->vars($data);
        $this->load->view('config_db/main');
    }

    /**
     * View config db filenames
     * http://dms2.pnl.gov/config_db/dir
     */
    function dir()
    {
        // get list of config files from config folder
        $file_filter = "/db/";
        $config_files = $this->_get_config_db_file_list($file_filter);
        asort($config_files);
        echo "<h3>Config DB Files</h3>\n";
        echo "| &nbsp;<a href='".$this->config->item('pwiki')."DMS_Config_DB_Help'>Help</a> &nbsp; | &nbsp;";
        echo "<ul>\n";
        foreach($config_files as $config_db) {
            $linkHtml = $this->_make_page_family_contents_link($config_db, $config_db);
            echo "<li>$linkHtml</li>";
        }
        echo "</ul>\n";
    }

    /**
     * Show Not Allowed message box
     * @param type $title
     * @param type $msg
     */
    function message($title="Not Allowed", $msg="This feature is not enabled for this version of DMS")
    {
            $data['title'] = $title;
            $data['heading'] = $data['title'];
            $data['message'] = $msg;
            $this->load->view('message_box', $data);
    }

    /**
     * Show Not Allowed message box, linking back to the contents page for this page family
     * @param string $config_db Config DB name, including .db
     */
    function show_not_allowed($config_db)
    {
        $linkHtml = $this->_make_page_family_contents_link($config_db, ucwords($config_db) . " Contents");
        $title = "Not Allowed";
        $msg = "This feature is not enabled for this version of DMS; back to $linkHtml";
        $this->message($title, $msg);
    }

    /**
     * Use the SQLite vacuum command to compact a database
     * @param string $config_db Config DB name, including .db
     */
    function vacuum_db($config_db)
    {
        $dbFilePath = $this->configDBPath.$config_db;
//      $before = filesize($dbFilePath);
        $dbh = new PDO("sqlite:$dbFilePath");
//      $dbh->beginTransaction();
        $dbh->query("VACUUM;");
//      $dbh->commit();
        $dbh = null;
//      $after = filesize($dbFilePath);
        echo "Config DB '$config_db' has been vacuumed.";
    }

    /**
     * Execute SQL against multiple config dbs
     * Note: statement "$this->_exec_sql" is commented out below for safety
     * http://dms2.pnl.gov/config_db/update_multiple
     */
    function update_multiple()
    {
        // do all config dbs
        $file_filter = "/.db/";

        // change to apply
        $table_name = "detail_report_hotlinks";
        $sql = "ALTER TABLE detail_report_hotlinks ADD options text;";

        // get list of config files from config folder
        $config_files = $this->_get_config_db_file_list($file_filter);
        asort($config_files);

        echo "Finding Config DBs with table " . $table_name . '<br><br>';

        // apply the change to each one
        foreach($config_files as $config_db) {
            // get list of config files that have table
            $tbl_list = $this->_get_db_table_list($config_db, $table_name);

            // skip config dbs that don't contain the table
            if(count($tbl_list) == 0)
                continue;

            echo $config_db . " ...";

            switch ($config_db)
            {
                case 'bionet.db':
                case 'instrument_usage_report.db':
                case 'master_config_db.db':
                case 'ncbi_taxonomy.db':
                case 'param_file.db':
                case 'protein_collection.db':
                case 'protein_collection_members.db':
                case 'user_operation.db':
                    echo "Update to add the options column<br>";
                    // Uncomment the following to actually execute the SQL against the given config db
                    // $this->_exec_sql($config_db, $sql, $table_name);
                    // echo "Updated " . $config_db;
                    break;
                default:
                    echo "Skip <br>";
            }
        }
    }

/*
    // --------------------------------------------------------------------
    // AJAX
    function show_tip($table_name, $col_name)
    {
        $def = array(
            "list_report_hotlinks" => array(
            "name"  => "Designates which column in the list report gets this hotlink. Matches against name of column",
            "LinkType"  => "(refer to the explanation on the help page)",
            "WhichArg"  => "Many hotlinks require a single parameter value. This field names the column that supplies that parameter. It can be either the same column that the hotlink appears in (in that case, use the special keyword 'value') or it can be another column.",
            "Target"  => "Identifies the thing that is the target of the action of the hotlink. For example, the partial URL of a page to go to when the hotlink is clicked.",
            "Options"  => "Parameters for some of the more complicated hotlink types"
            ),
        );
        $s = "Tooltip text for '$col_name' would be here";
        if (array_key_exists($table_name, $def)) {
            if (array_key_exists($col_name, $def[$table_name])) {
                $s = $def[$table_name][$col_name];
            }
        }
        echo $s;
    }
*/

}
?>
