<?php

class Dms_menu extends CI_Model {

    var $configDBFolder = "";

    /**
     * Constructor
     */
    function __construct() {
        //Call the Model constructor
        parent::__construct();
        $this->configDBFolder = $this->config->item('model_config_path');
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
        $dbFilePath = $this->configDBFolder . $config_db_name;
        $dbh = new PDO("sqlite:$dbFilePath");
        if (!$dbh) {
            throw new Exception('Could not connect to menu config database at ' . $dbFilePath);
        }

        foreach ($dbh->query("SELECT * FROM $section_def_table", PDO::FETCH_ASSOC) as $row) {
            $section_name = $row['section_name'];
            $sections[$section_name] = $row;
            $sections[$section_name]['section_menu_items'] = array();
        }
        foreach ($dbh->query("SELECT * FROM $section_item_table", PDO::FETCH_ASSOC) as $row) {
            $section_name = $row['section_name'];
            $sections[$section_name]['section_menu_items'][] = $row;
        }
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
        $dbFilePath = $this->configDBFolder . $config_db_name;
        $dbh = new PDO("sqlite:$dbFilePath");
        if (!$dbh) {
            throw new Exception('Could not connect to menu config database at ' . $dbFilePath);
        }

        $mnu = array();
        foreach ($dbh->query("SELECT * FROM $menu_def_table", PDO::FETCH_ASSOC) as $row) {
            $mnu[] = $row;
        }
        return $mnu;
    }

}
