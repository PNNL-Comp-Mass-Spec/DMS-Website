<?php

class Config_db_model extends CI_Model {

    var $table_defs = array();
    var $table_edit_col_defs = array();
    var $masterConfigDBPath = "";

    // --------------------------------------------------------------------
    function __construct() 
    {
        //Call the Model constructor
        parent::__construct();

        $this->masterConfigDBPath = $this->config->item('model_config_path') . "master_config_db.db";   
        $this->initialize_table_defs();
        $this->initialize_table_field_defs();
    }

    // --------------------------------------------------------------------
    function initialize_table_defs()
    {
        $dbh = new PDO("sqlite:$this->masterConfigDBPath");
        foreach ($dbh->query("SELECT * FROM table_def_description", PDO::FETCH_ASSOC) as $row) {
            $t = $row['config_table'];
            $this->table_defs[$t]['description'] = $row['value'];;
        }
        foreach ($dbh->query("SELECT * FROM table_def_sql", PDO::FETCH_ASSOC) as $row) {
            $t = $row['config_table'];
            $this->table_defs[$t]['sql'] = $row['value'];;
        }
    }

    // --------------------------------------------------------------------
    function initialize_table_field_defs()
    {
        $dbh = new PDO("sqlite:$this->masterConfigDBPath");
        foreach ($dbh->query("SELECT * FROM table_edit_col_defs", PDO::FETCH_ASSOC) as $row) {
            $t = $row['config_table'];
            $c = $row['config_col'];
            $y = array();
            $y['type'] = $row['type'];
            $y['value'] = $row['value'];
            $this->table_edit_col_defs[$t][$c] = $y;
        }
    }

    // --------------------------------------------------------------------
    function get_table_list()
    {
        return array_keys($this->table_defs);
    }

    // --------------------------------------------------------------------
    function get_table_def($table_name, $category)
    {
        $s = "";
        if(array_key_exists($table_name, $this->table_defs)) {
            if(array_key_exists($category, $this->table_defs[$table_name])) {
                $s .= $this->table_defs[$table_name][$category];
            }
        }
        return $s;
    }

    // --------------------------------------------------------------------
    function get_edit_table_entry_field_def($table_name, $col_name)
    {
        $obj = new stdClass();
        $obj->type = "empty";
        if(array_key_exists($table_name, $this->table_edit_col_defs)) {
            if(array_key_exists($col_name, $this->table_edit_col_defs[$table_name])) {
                $json = $this->table_edit_col_defs[$table_name][$col_name]['value'];
                $obj = json_decode($json);
                $obj->type = $this->table_edit_col_defs[$table_name][$col_name]['type'];
            }
        }
        return $obj;
    }

}
