<?php
namespace App\Controllers;

class Osm_package extends Base_controller {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->my_tag = "osm_package";
        $this->my_title = "OSM Package";
    }

/* OMCS-977
    // --------------------------------------------------------------------
    // (someday) use q_model
    function suggested_items($id, $mode) {
        helper(['url', 'text']);

        $this->db = \Config\Database::connect();

        $sql = "SELECT dbo.GetOSMItemChooserList($id, '$mode')";
        $query = $this->db->query($sql);
        if(!$query) return "Error querying database";
        if ($query->getNumRows() == 0) return "No rows found";
        $result = $query->getRow();
        echo $result->computed;
    }
 */
}
?>
