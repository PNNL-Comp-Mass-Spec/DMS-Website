<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\SQLite3\Connection;

class Config_db_model extends Model {

    var $table_defs = array();
    var $table_edit_col_defs = array();
    var $masterConfigDBPath = "";

    // --------------------------------------------------------------------
    function __construct() {
        //Call the Model constructor
        parent::__construct();

        helper(['config_db']);
        $this->masterConfigDBPath = get_model_config_db_path("master_config_db.db")->path;
        $this->initialize_table_defs();
        $this->initialize_table_field_defs();
    }

    // --------------------------------------------------------------------
    function initialize_table_defs() {
        $db = new Connection(['database' => $this->masterConfigDBPath, 'dbdriver' => 'sqlite3']);
        foreach ($db->query("SELECT * FROM table_def_description")->getResultArray() as $row) {
            $t = $row['config_table'];
            $this->table_defs[$t]['description'] = $row['value'];
        }
        foreach ($db->query("SELECT * FROM table_def_sql")->getResultArray() as $row) {
            $t = $row['config_table'];
            $this->table_defs[$t]['sql'] = $row['value'];
        }

        $db->close();
    }

    // --------------------------------------------------------------------
    function initialize_table_field_defs() {
        $db = new Connection(['database' => $this->masterConfigDBPath, 'dbdriver' => 'sqlite3']);
        foreach ($db->query("SELECT * FROM table_edit_col_defs")->getResultArray() as $row) {
            $t = $row['config_table'];
            $c = $row['config_col'];
            $y = array();
            $y['type'] = $row['type'];
            $y['value'] = $row['value'];
            $this->table_edit_col_defs[$t][$c] = $y;
        }

        $db->close();
    }

    // --------------------------------------------------------------------
    function get_table_list() {
        return array_keys($this->table_defs);
    }

    // --------------------------------------------------------------------
    function get_table_def($table_name, $category) {
        $s = "";
        if (array_key_exists($table_name, $this->table_defs)) {
            if (array_key_exists($category, $this->table_defs[$table_name])) {
                $s .= $this->table_defs[$table_name][$category];
            }
        }
        return $s;
    }

    // --------------------------------------------------------------------
    function get_edit_table_entry_field_def($table_name, $col_name) {
        $obj = new \stdClass();
        $obj->type = "empty";
        if (array_key_exists($table_name, $this->table_edit_col_defs)) {
            if (array_key_exists($col_name, $this->table_edit_col_defs[$table_name])) {
                $json = $this->table_edit_col_defs[$table_name][$col_name]['value'];
                $obj = json_decode($json);
                $obj->type = $this->table_edit_col_defs[$table_name][$col_name]['type'];
            }
        }
        return $obj;
    }
}
?>
