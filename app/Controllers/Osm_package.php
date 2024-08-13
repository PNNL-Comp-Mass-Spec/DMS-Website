<?php
namespace App\Controllers;

class Osm_package extends DmsBase {
    function __construct()
    {
        $this->my_tag = "osm_package";
        $this->my_title = "OSM Package";
    }

/* OMCS-977
    // --------------------------------------------------------------------
    // (someday) use Q_model
    function suggested_items($id, $mode) {
        helper(['url', 'text']);

        $this->db = \Config\Database::connect();
        $this->updateSearchPath($this->db);

        $sql = "SELECT * FROM get_osm_item_chooser_list($id, '$mode')";
        $query = $this->db->query($sql);
        if(!$query) return "Error querying database";
        if ($query->getNumRows() == 0) return "No rows found";
        $result = $query->getRow();
        echo $result->computed;
    }
 */
}
?>
