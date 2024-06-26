<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\SQLite3\Connection;

class Dms_chooser extends Model {

    /**
     * This array defines the selection list choosers and includes both cases
     * (direct list of options vs query db for options)
     * @var type
     */
    var $choices = array();

    /**
     * Constructor
     */
    function __construct() {
        //Call the Model constructor
        parent::__construct();
        $this->load_choosers();
    }

    /**
     * Initialize the list of choosers from the config db file
     */
    function load_choosers() {
        helper(['config_db']);
        $dbFilePath = get_model_config_db_path("dms_chooser.db")->path;
        $db = new Connection(['database' => $dbFilePath, 'dbdriver' => 'sqlite3']);
        $r = $db->query("SELECT * FROM chooser_definitions")->getResultArray();
        $db->close();
        foreach ($r as $row) {
            $def = array();
            $def['db'] = $row['db'];
            $type = $row['type'];
            $def['type'] = $type;
            switch ($type) {
                case 'select';
                    $def['value'] = json_decode($row['value'], true);
                    break;
                case 'sql';
                    $def['value'] = $row['value'];
                    break;
            }
            $this->choices[$row['name']] = $def;
        }
    }

    /**
     * Return list of chooser specs
     * @return type
     */
    function get_choosers() {
        return $this->choices;
    }

    /**
     * Return sorted list of chooser names
     * @return type
     */
    function get_chooser_names() {
        $cl = array_keys($this->choices);
        natcasesort($cl);
        return $cl;
    }

    /**
     * Return choices list for given chooser
     * @param type $chooser_name
     * @return type
     */
    function get_choices($chooser_name) {
        $options = array();
        helper(['string', 'database']);
        if (array_key_exists($chooser_name, $this->choices)) {
            switch ($this->choices[$chooser_name]["type"]) {
                case "select":
                    $options[""] = "-- choices --";
                    foreach ($this->choices[$chooser_name]["value"] as $k => $v) {
                        $options[$k] = $v;
                    }
                    break;
                case "sql":
                    $db = "default";
                    if (array_key_exists("db", $this->choices[$chooser_name])) {
                        $db = $this->choices[$chooser_name]["db"];
                    }
                    $my_db = \Config\Database::connect(GetNullIfBlank($db));
                    update_search_path($my_db);

                    $result = $my_db->query($this->choices[$chooser_name]["value"]);
                    if ($result) {
                        $options[""] = "-- choices --";
                        foreach ($result->getResultArray() as $row) {
                            $val = $row["val"];
                            $ex = (string) $row["ex"];
                            $ex = ($ex != '') ? $ex : $val;
                            $options[$ex] = $row["val"];
                        }
                    }
                    break;
            }
        }
        return $options;
    }

    /**
     * Return choices list for given chooser, optionally filtering the values
     * Returns up to 500 items (sorted by ID), to prevent dropdown lists from being too long
     * @param type $chooser_name
     * @param type $filter_value
     * @return \stdClass
     */
    function get_filtered_choices($chooser_name, $filter_value) {
        $filterValueClean = str_ireplace('*', '', $filter_value);
        $returnListLimit = "500";
        $options = array();
        helper(['string', 'database']);
        if (array_key_exists($chooser_name, $this->choices)) {
            switch ($this->choices[$chooser_name]["type"]) {
                case "select":
                    foreach ($this->choices[$chooser_name]["value"] as $k => $v) {
                        $obj = new \stdClass();
                        $obj->label = $v;
                        $obj->value = $k;
                        $options[] = $obj;
                    }
                    break;

                case "sql":
                    $db = "default";
                    if (array_key_exists("db", $this->choices[$chooser_name])) {
                        $db = $this->choices[$chooser_name]["db"];
                    }
                    $my_db = \Config\Database::connect(GetNullIfBlank($db));
                    update_search_path($my_db);

                    $sql = $this->choices[$chooser_name]["value"];
                    if ($filterValueClean) {
                        $sx = $sql;
                        $sqlsrvLimit = "";
                        $postgresLimit = "";
                        if (strcasecmp($my_db->driver, 'sqlsrv') == 0 || strcasecmp($my_db->driver, 'mssql') == 0) {
                            // SQL Server requires 'TOP' or 'FOR XML' to allow use of ORDER BY in subqueries
                            $sx = str_ireplace('select', 'SELECT TOP 100 PERCENT', $sql);
                            $sqlsrvLimit = " TOP " . $returnListLimit;
                        } else {
                            $postgresLimit = " LIMIT " . $returnListLimit;
                        }
                        $sql = "SELECT" . $sqlsrvLimit . " * FROM ($sx) TX WHERE val LIKE '%$filterValueClean%'" . $postgresLimit;
                    }
                    $result = $my_db->query($sql);
                    if ($result) {
                        foreach ($result->getResultArray() as $row) {
                            $obj = new \stdClass();
                            $label = $row["val"];
                            $value = (string) $row["ex"];
                            $obj->label = $label;
                            if ($value != '') {
                                $obj->value = $value;
                            } else {
                                $obj->value = $label;
                            }
                            $options[] = $obj;
                        }
                    }
                    break;
            }
        }
        return $options;
    }

    /**
     * Return HTML for a drop-down selector and suitable options for the specified chooser_name.
     * @param type $target_field_name Field Name
     * @param type $chooser_name Chooser name (aka pick list name)
     * @param type $mode Chooser mode (append, append_comma, prepend, prepend_comma, prepend_underscore, or replace)
     * @param type $seq Sequence ID (1, 2, 3, etc.)
     * @return string
     */
    function get_chooser($target_field_name, $chooser_name, $mode = 'replace', $seq = '') {
        $str = "";
        $chooser_element_name = $target_field_name . "_chooser" . $seq;
        $js = "id=\"$chooser_element_name\" class=\"sel_chooser\" ";

        // Define the onchange function, which is defined in DMS2/javascript/dmsChooser.js
        $js .= " onChange='dmsChooser.setFieldValueFromSelection(\"$target_field_name\", \"$chooser_element_name\", \"$mode\")'";
        if (!array_key_exists($chooser_name, $this->choices)) {
            $str .= "The chooser name '$chooser_name' could not be found";
            return $str;
        }
        switch ($this->choices[$chooser_name]["type"]) {
            case "select":
                $options = $this->get_choices($chooser_name);
                $str .= form_dropdown($chooser_element_name, $options, '', $js);
                return $str;
            case "sql":
                $options = $this->get_choices($chooser_name);
                $str .= form_dropdown($chooser_element_name, $options, '', $js);
                break;
            default:
                $str .= "The chooser type was not recognized.";
                break;
        }
        return $str;
    }

    /**
     * Create a set of choosers from the list in the given field spec
     * @param type $field_name
     * @param type $f_spec
     * @param type $element_start
     * @param type $element_end
     * @return string
     */
    function make_choosers($field_name, $f_spec, $element_start = "<div style='margin-bottom:5px;'>", $element_end = "</div>") {
        $s = "";
        $seq = 0;
        if (array_key_exists("chooser_list", $f_spec)) {
            foreach ($f_spec['chooser_list'] as $chsr) {
                $seq++;
                $pln = $chsr["PickListName"];
                $delim = $chsr['Delimiter'];
                $type = $chsr["type"];
                $target = $chsr['Target'];
                $xref = $chsr['XRef'];
                $label = (array_key_exists('Label', $chsr)) ? $chsr['Label'] : 'Choose from:';
                $ch = $this->make_chooser($field_name, $type, $pln, $target, $label, $delim, $xref, $seq);
                $s .= $element_start . $ch . $element_end;
            }
        }
        return $s;
    }

    /**
     * Create a chooser from the given parameters
     * @param type $f_name Field Name
     * @param type $type Chooser type
     * @param type $pln Chooser name (aka pick list name); empty string when the type is 'list-report.helper'
     * @param type $target Target helper page (only used if the type is 'list-report.helper')
     * @param type $label Text to show before the chooser dropdown or chooser list
     * @param type $delim Delimiter to use when selecting multiple items
     * @param type $xref Field name whose contents should be sent to the helper page when the type is 'list-report.helper'
     * @param type $seq Sequence ID (1, 2, 3, etc.)
     * @return string
     */
    function make_chooser($f_name, $type, $pln, $target, $label, $delim, $xref, $seq = '1') {
        $str = "";
        switch ($type) {
            case "picker.prepend":
            	// Place the selected item at the start of the list, followed by a semicolon by default.
            	// If the delimiter column has a comma or underscore, use that instead
                if ($delim == ',') {
                    $mode = 'prepend_comma';
                } else if ($delim == '_') {
                    $mode = 'prepend_underscore';
                } else {
                    $mode = 'prepend';
                }

                $str .= "$label " . $this->get_chooser($f_name, $pln, $mode, $seq);
                break;

            case "picker.append":
            	// Append the selected item, separating with a semicolon by default.
            	// If the delimiter column has a comma, use a comma instead (no other delimiters are supported)
                $mode = ($delim == ',') ? 'append_comma' : 'append';
                $str .= "$label " . $this->get_chooser($f_name, $pln, $mode, $seq);
                break;

            case "picker.replace":
            	// Replace the text with the selected item; this is the most commonly used chooser
            	// Drop-down choosers are defined in dms_chooser.db.
                $mode = 'replace';
                $str .= "$label " . $this->get_chooser($f_name, $pln, $mode, $seq);
                break;

            case "list-report.helper":
            	// Choose an item from a list report
                helper(['text']);
                $target_url = reduce_double_slashes(site_url($target));
                $str .= "$label <a href=\"javascript:dmsChooser.callChooser('$f_name', '$target_url', '$delim', '$xref')\"><img src='" . base_url('images/chooser.png') . "' border='0'></a>";
                break;

            case "picker.prevDate":
            	// Show the user a calendar to allow them to select a date.
            	// The PickListName was previously either prevDatePickList or futureDatePickList, but now that we're using a calendar, the name doesn't matter.
                $str .= "$label <a href=\"javascript:dmsChooser.callDatepicker('$f_name')\"><img src='" . base_url('images/date.png') . "' border='0'></a>";
                break;

            case "picker.list":
            	// Show the user a list of item names and descriptions.
            	// When the user clicks a name, that name is placed in the textbox associated with the picker.
            	// Drop-down choosers are defined in dms_chooser.db, as described below for picker.replace
                $str .= "$label " . $this->get_list_chooser($f_name, $pln);
                break;

            case "link.list":
            	// Show the user a list of template names.
            	// When the user clicks one, pre-canned text is placed in the associated list box.
            	// Templates are defined with the other DMS choosers in dms_chooser.db
                $str .= "$label " . $this->get_link_chooser($f_name, $pln);
                break;
        }
        return $str;
    }

    /**
     * Get list chooser
     * @param type $target_field_name
     * @param type $chooser_name
     * @return string
     */
    function get_list_chooser($target_field_name, $chooser_name) {
        $str = '';
        $options = $this->get_choices($chooser_name);
        $str .= "<table>";
        foreach ($options as $k => $v) {
            if ($k) {
                $lnk = "<a href='javascript:dmsChooser.setFieldValue(\"$target_field_name\", \"$k\")' >$k</a>";
                $str .= "<tr><td>$lnk</td><td>$v</td></tr>";
            }
        }
        $str .= "</table>";
        return $str;
    }

    /**
     * Get link chooser
     * @param type $target_field_name
     * @param type $chooser_name
     * @return string
     */
    function get_link_chooser($target_field_name, $chooser_name) {
        $str = '';
        $options = $this->get_choices($chooser_name);
        $str .= "<table>";
        foreach ($options as $k => $v) {
            if ($k) {
                $lnk = "<a href='javascript:void(0)' onclick='dmsChooser.setFieldTemplateValue(\"$target_field_name\", \"$v\")' >$k</a>";
                $str .= "<tr><td>$lnk</td></tr>";
            }
        }
        $str .= "</table>";
        return $str;
    }
}
?>
