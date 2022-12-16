<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\SQLite3\Connection;

class Dms_menu extends Model {

    /**
     * Constructor
     */
    function __construct() {
        //Call the Model constructor
        parent::__construct();
    }

    /**
     * Section menu has section def and section item def tables that get
     * combined into one nested array
     * @param type $config_db_name
     * @param type $section_def_table
     * @param type $section_item_table
     * @return type
     * @throws Exception
     */
    function get_section_menu_def($config_db_name, $section_def_table, $section_item_table) {
        $sections = array();
        helper(['config_db']);
        $dbFilePath = get_model_config_db_path($config_db_name)->path;
        $db = new Connection(['database' => $dbFilePath, 'dbdriver' => 'sqlite3']);

        foreach ($db->query("SELECT * FROM $section_def_table")->getResultArray() as $row) {
            $section_name = $row['section_name'];
            $sections[$section_name] = $row;
            $sections[$section_name]['section_menu_items'] = array();
        }
        foreach ($db->query("SELECT * FROM $section_item_table")->getResultArray() as $row) {
            $section_name = $row['section_name'];
            $sections[$section_name]['section_menu_items'][] = $row;
        }

        $db->close();
        return $sections;
    }

    /**
     * Simple menu single def table that gets loaded into an array
     * @param type $config_db_name
     * @param type $menu_def_table
     * @return type
     * @throws Exception
     */
    function get_menu_def($config_db_name, $menu_def_table) {
        helper(['config_db']);
        $dbFilePath = get_model_config_db_path($config_db_name)->path;
        $db = new Connection(['database' => $dbFilePath, 'dbdriver' => 'sqlite3']);

        $mnu = array();
        foreach ($db->query("SELECT * FROM $menu_def_table")->getResultArray() as $row) {
            $mnu[] = $row;
        }

        $db->close();
        return $mnu;
    }
}
?>
