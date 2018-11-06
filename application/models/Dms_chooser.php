<?php

class Dms_chooser extends CI_Model {

    /**
     * This array defines the selection list choosers and includes both cases
     * (direct list of options vs query db for options)
     * @var type
     */
    var $choices = array();

    /**
     * Constructor
     */
    function __construct()
    {
        //Call the Model constructor
        parent::__construct();
        $this->load_choosers();
    }

    /**
     * Initialize the list of choosers from the config db file
     */
    function load_choosers()
    {
        $dbFilePath = $this->config->item('model_config_path') . "/dms_chooser.db";
        $dbh = new PDO("sqlite:$dbFilePath");
        $r = $dbh->query("SELECT * FROM chooser_definitions", PDO::FETCH_ASSOC);
        foreach ($r as $row) {
            $def = array();
            $def['db'] = $row['db'];
            $type = $row['type'];
            $def['type'] = $type;
            switch($type) {
                case 'select';
                    $def['value'] = json_decode($row['value'], TRUE);
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
    function get_choosers()
    {
        return $this->choices;
    }

    /**
     * Return sorted list of chooser names
     * @return type
     */
    function get_chooser_names()
    {
        $cl = array_keys($this->choices);
        natcasesort($cl);
        return $cl;
    }

    /**
     * Return choices list for given chooser
     * @param type $chooser_name
     * @return type
     */
    function get_choices($chooser_name)
    {
        $options = array();
        if(array_key_exists($chooser_name, $this->choices)) {
            switch($this->choices[$chooser_name]["type"]) {
                case "select":
                    $options[""] = "-- choices --";
                    foreach($this->choices[$chooser_name]["value"] as $k=>$v) {
                        $options[$k] = $v;
                    }
                    break;
                case "sql":
                    $db = "default";
                    if(array_key_exists("db", $this->choices[$chooser_name])) {
                        $db = $this->choices[$chooser_name]["db"];
                    }
                    $my_db = $this->load->database($db, TRUE);
                    $result = $my_db->query($this->choices[$chooser_name]["value"]);
                    if($result) {
                        $options[""] = "-- choices --";
                        foreach($result->result_array() as $row) {
                            $val = $row["val"];
                            $ex = (string)$row["ex"];
                            $ex = ($ex != '')?$ex:$val;
                            $options[$ex] = $row["val"];
                        }
                    }
                    break;
            }
        }
        return $options;
    }

    /**
     * Return choices list for given chooser
     * @param type $chooser_name
     * @param type $filter_value
     * @return \stdClass
     */
    function get_filtered_choices($chooser_name, $filter_value)
    {
        $filter_value = str_ireplace('*', '', $filter_value);
        $options = array();
        if(array_key_exists($chooser_name, $this->choices)) {
            switch($this->choices[$chooser_name]["type"]) {
                case "select":
                    foreach($this->choices[$chooser_name]["value"] as $k=>$v) {
                        $obj = new stdClass();
                        $obj->label = $v;
                        $obj->value = $k;
                        $options[] = $obj;
                    }
                    break;
                case "sql":
                    $db = "default";
                    if(array_key_exists("db", $this->choices[$chooser_name])) {
                        $db = $this->choices[$chooser_name]["db"];
                    }
                    $my_db = $this->load->database($db, TRUE);
                    $sql = $this->choices[$chooser_name]["value"];
                    if($filter_value) {
                        $sx = str_ireplace('select', 'SELECT TOP 100 PERCENT', $sql);
                        $sql = "SELECT * FROM ($sx) TX WHERE val LIKE '%$filter_value%'";
                    }
                    $result = $my_db->query($sql);
                    if($result) {
                        foreach($result->result_array() as $row) {
                            $obj = new stdClass();
                            $label = $row["val"];
                            $value = (string)$row["ex"];
                            $obj->label = $label;
                            if ($value != '') {
                                $obj->value = $value ;
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
    function get_chooser($target_field_name, $chooser_name, $mode = 'replace', $seq = '')
    {
        $str = "";
        $chooser_element_name = $target_field_name . "_chooser" . $seq;
        $js = "id=\"$chooser_element_name\" class=\"sel_chooser\" ";
        $js .= " onChange='epsilon.setFieldValueFromSelection(\"$target_field_name\", \"$chooser_element_name\", \"$mode\")'";
        if(!array_key_exists($chooser_name, $this->choices)) {
            $str .=  "The chooser name '$chooser_name' could not be found";
            return $str;
        }
        switch($this->choices[$chooser_name]["type"]) {
            case "select":
                $options = $this->get_choices($chooser_name);
                $str .=  form_dropdown($chooser_element_name, $options, '', $js);
                return $str;
            case "sql":
                $options = $this->get_choices($chooser_name);
                $str .=  form_dropdown($chooser_element_name, $options, '', $js);
                break;
            default:
                $str .=  "The chooser type was not recognized.";
                break;
        }
        return $str;
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
    function make_chooser($f_name, $type, $pln, $target, $label, $delim, $xref, $seq = '1')
    {
        $str = "";
        switch($type){
            case "picker.prepend":
                if ($delim == ',')
                    $mode = 'prepend_comma';
                else if ($delim == '_')
                    $mode = 'prepend_underscore';
                else
                    $mode = 'prepend';

                $str .= "$label ".$this->get_chooser($f_name, $pln, $mode, $seq);
                break;
            case "picker.append":
                $mode = ($delim == ',') ? 'append_comma' : 'append';
                $str .= "$label ".$this->get_chooser($f_name, $pln, $mode, $seq);
                break;
            case "picker.replace":
                $mode = 'replace';
                $str .= "$label ".$this->get_chooser($f_name, $pln, $mode, $seq);
                break;
            case "list-report.helper":
                $CI =& get_instance();
                $CI->load->helper(array('string'));
                $target_url = reduce_double_slashes(site_url().$target);
                $str .= "$label <a href=\"javascript:epsilon.callChooser('$f_name', '$target_url', '$delim', '$xref')\"><img src='".base_url()."images/chooser.png' border='0'></a>";
                break;
            case "picker.prevDate":
                $str .= "$label <a href=\"javascript:epsilon.callDatepicker('$f_name')\"><img src='".base_url()."images/date.png' border='0'></a>";
                break;
            case "picker.list":
                $str .= "$label ".$this->get_list_chooser($f_name, $pln, 'replace', $seq);
                break;
            case "link.list":
                $str .= "$label ".$this->get_link_chooser($f_name, $pln, 'replace', $seq);
                break;
            case "autocomplete":
            case "autocomplete.append":
                $str .= "(choices will appear when you start typing)";
                break;
        }
        return $str;
    }

    /**
     * Get list chooser
     * @param type $target_field_name
     * @param type $chooser_name
     * @param type $mode Unused
     * @param type $seq Unused
     * @return string
     */
    function get_list_chooser($target_field_name, $chooser_name, $mode = 'replace', $seq = '')
    {
        $str = '';
        $options = $this->get_choices($chooser_name);
        $str .= "<table>";
        foreach($options as $k => $v) {
            if($k) {
                $lnk = "<a href='javascript:epsilon.setFieldValue(\"$target_field_name\", \"$k\")' >$k</a>";
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
     * @param type $mode Unused
     * @param type $seq Unused
     * @return string
     */
    function get_link_chooser($target_field_name, $chooser_name, $mode = 'replace', $seq = '')
    {
        $str = '';
        $options = $this->get_choices($chooser_name);
        $str .= "<table>";
        foreach($options as $k => $v) {
            if($k) {
                $lnk = "<a href='javascript:void(0)' onclick='epsilon.setFieldTemplateValue(\"$target_field_name\", \"$v\")' >$k</a>";
                $str .= "<tr><td>$lnk</td></tr>";
            }
        }
        $str .= "</table>";
        return $str;
    }

}
