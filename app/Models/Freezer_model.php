<?php
namespace App\Models;

use CodeIgniter\Model;

class Freezer_model extends Model {

    // Freezer name is column Freezer_Tag in tables T_Material_Freezers and T_Material_Locations, and in views V_Material_Container_Locations and V_Material_Locations
    // Freezer name is column ? in views
    var $hierarchy = array(
        "freezer" => "shelf",
        "shelf" => "rack",
        "rack" => "row",
        "row" => "col",
        "col" => "",
        "tag" => "tag"
    );

    // --------------------------------------------------------------------
    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // --------------------------------------------------------------------
    function check_connection() {
        return true;
    }

    // --------------------------------------------------------------------
    function get_sub_location_type($type) {
        return $this->hierarchy[$type];
    }

    /**
     * https://dms2.pnl.gov/freezer/get_freezers
     * @return array
     * @throws \Exception
     */
    function get_freezers(): array {
        $sql = <<<EOD
SELECT RankQ.tag, RankQ.freezer, RankQ.shelf, RankQ.rack, RankQ.row, RankQ.col, RankQ.comment,
       RankQ.limit, RankQ.containers, RankQ.available, RankQ.status, RankQ.id
FROM ( SELECT Location As tag, Freezer_Tag As freezer, shelf, rack, row, col, comment,
              Container_Limit As limit, containers, available, status, id,
              Row_Number() Over (Partition By Freezer Order By Case When Shelf= 'na' Then 0 Else 1 End, Shelf, Rack) As RankValue
       FROM V_Material_Location_List_Report
    ) RankQ
WHERE RankQ.RankValue = 1
EOD;
        $query = $this->db->query($sql);
        if (!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for freezers; see writable/logs/log-$currentTimestamp.php");
        }
        return $query->getResultArray();
    }

    /**
     * @param string $Type Location type: Shelf, Rack, Row, Col, or Tag
     * @param string $Freezer Freezer name, but if $Type = 'Tag', this is location name
     * @param string|int $Shelf Shelf number (ignored if $Type is Shelf or Tag
     * @param string|int $Rack Rack number (ignored if $Type is Shelf, Rack, or Tag
     * @param string|int $Row Row number (only used if $Type is Col)
     * @return array
     */
    function get_locations(string $Type, string $Freezer, $Shelf, $Rack, $Row): array {
        $sql = <<<EOD
SELECT Location As tag, Freezer_Tag As freezer, shelf, rack, row, col, comment,
       Container_Limit As limit, containers, available, status, id
FROM V_Material_Location_List_Report
EOD;
        switch (strtolower($Type)) {
            case 'shelf':
                $sql .= " WHERE Freezer_Tag = '$Freezer' AND Rack = 'na' AND NOT Shelf = 'na' ";
                break;
            case 'rack':
                $sql .= " WHERE Freezer_Tag = '$Freezer' AND Shelf = '$Shelf' AND Row = 'na' AND NOT Rack = 'na'";
                break;
            case 'row':
                $sql .= " WHERE Freezer_Tag = '$Freezer' AND Shelf = '$Shelf' AND Rack = '$Rack'  AND  Col = 'na' AND NOT Row = 'na'";
                break;
            case 'col':
                $sql .= " WHERE Freezer_Tag = '$Freezer' AND Shelf = '$Shelf' AND Rack = '$Rack'  AND  Row = '$Row' AND NOT Col = 'na'";
                break;
            case 'tag':
                $sql .= " WHERE Location IN ($Freezer)";
                break;
        }
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/get_containers/1206C.3.2.1.1
    //
    // Bug: web page does not show results
    //
    function get_containers($location) {
        $sql = <<<EOD
SELECT container, type, location, items, files, comment, action, created, campaigns, researcher, id
FROM V_Material_Containers_List_Report
EOD;
        $sql .= " WHERE Location = '$location'";
        $query = $this->db->query($sql);
        if (!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for containers; see writable/logs/log-$currentTimestamp.php");
        }
        return $query->getResultArray();
    }

    // --------------------------------------------------------------------
    function get_material($container) {
        $sql = <<<EOD
SELECT item_type, item, id
FROM V_Material_Items_List_Report
EOD;
        $sql .= " WHERE Container = '$container'";
        $query = $this->db->query($sql);
        if (!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for material items; see writable/logs/log-$currentTimestamp.php");
        }
        return $query->getResultArray();
    }

    // --------------------------------------------------------------------
    function get_location_type($location) {
        $type = "freezer";
        $locs = array_keys($this->hierarchy);
        array_pop($locs);
        foreach ($locs as $loc) {
            $type = $loc;
            if (!$this->hierarchy[$loc]) {
                break;
            }
            if ($location[$this->hierarchy[$loc]] == "na") {
                break;
            }
        }
        return $type;
    }

    // --------------------------------------------------------------------
    function build_freezer_location_list($Type, $locations, $controller) {
        $items = array();

        if (!$controller->check_access('operation', false)) {
            // User does not have permission to update items on this page
            // Return some dummy values

            $obj = new \stdClass();
            $obj->title = "Access denied: cannot update";
            $obj->folder = true;
            $obj->lazy = true;
            $obj->key = "000";

            $info = new \stdClass();
            $info->Name = "Access denied: cannot update";
            $info->Type = "Shelf";
            $info->ID = "000";
            $info->Tag = "x";
            $info->Freezer = "Non existent freezer";
            $info->Shelf = "0";
            $info->Rack = "0";
            $info->Row = "0";
            $info->Col = "0";
            $info->Status = "Undefined";
            // $info->Barcode = "";
            $info->Comment = "";
            $info->Limit = 0;
            $info->Containers = 0;
            $info->Available = "No";
            $obj->info = $info;

            $items[] = $obj;
        } else {
            // User does have permission to update items on this page

            foreach ($locations as $entry) {
                if (!$Type) {
                    $Type = $this->get_location_type($entry);
                }
                $name = $entry[strtolower($Type)];
                $obj = new \stdClass();
                $obj->title = "$Type $name";
                $obj->folder = true;
                $obj->lazy = true;
                $obj->key = $entry['tag'];

                $info = new \stdClass();
                $info->Name = $name;
                $info->Type = $Type;
                $info->ID = $entry['id'];
                $info->Tag = $entry['tag'];
                $info->Freezer = $entry['freezer'];
                $info->Shelf = $entry['shelf'];
                $info->Rack = $entry['rack'];
                $info->Row = $entry['row'];
                $info->Col = $entry['col'];
                $info->Status = $entry['status'];
                // $info->Barcode = $entry['barcode'];
                $info->Comment = $entry['comment'];
                $info->Limit = $entry['limit'];
                $info->Containers = $entry['containers'];
                $info->Available = $entry['available'];
                $obj->info = $info;

                $items[] = $obj;
            }
        }
        return $items;
    }

    // --------------------------------------------------------------------
    function build_container_list($containers) {
        $items = array();
        foreach ($containers as $entry) {
            $name = $entry["Container"];
            $obj = new \stdClass();
            $obj->title = $name;
            $obj->folder = false;
            $obj->lazy = true;
            $obj->key = $name;
            $obj->children = [];
            //          $obj->hideCheckbox = true;

            $info = new \stdClass();
            $info->Name = $name;
            $info->Type = "Container";
            $info->Container = $entry['container'];
            $info->ContainerType = $entry['type'];
            $info->Location = $entry['location'];
            $info->Items = $entry['items'];
            $info->Files = $entry['files'];
            $info->Comment = $entry['comment'];
            //$info->Barcode = $entry['barcode'];
            $info->Created = $entry['created'];
            $info->Campaigns = $entry['campaigns'];
            $info->Researcher = $entry['researcher'];
            $info->ID = $entry['id'];
            $obj->info = $info;

            $items[] = $obj;
        }
        return $items;
    }

    // --------------------------------------------------------------------
    function build_material_item_list($material_items) {
        $items = array();
        foreach ($material_items as $entry) {
            $name = "{$entry['item_type']} {$entry['item']}";
            $obj = new \stdClass();
            $obj->title = $name;
            $obj->folder = false;
            $obj->lazy = false;
            $obj->hideCheckbox = true;

            $info = new \stdClass();
            $info->Name = $name;
            $info->Type = "Material";
            $info->Item_Type = $entry['item_type'];
            $info->Item = $entry['item'];
            $info->ID = $entry['id'];
            $obj->info = $info;

            $items[] = $obj;
        }
        return $items;
    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/find_container/MC-4581
    //
    // Bug: web page does not show results
    //
    function find_container($container) {
        $sql = <<<EOD
SELECT container, type, location, items, files, comment, action, created, campaigns, researcher, id
FROM V_Material_Containers_List_Report
EOD;
        $sql .= " WHERE Container = '$container'";
        $query = $this->db->query($sql);
        if (!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for container; see writable/logs/log-$currentTimestamp.php");
        }
        return $query->getResultArray();
    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/find_location/1206C.3.2.1.1
    //
    // Bug: web page does not show results
    //
    function find_location($location) {
        $sql = <<<EOD
SELECT Location As tag, Freezer_Tag As freezer, shelf, rack, row, col, comment,
       Container_Limit As limit, containers, available, status, id
FROM V_Material_Location_List_Report
EOD;
        $sql .= " WHERE Location = '$location'";
        $query = $this->db->query($sql);
        if (!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for material location; see writable/logs/log-$currentTimestamp.php");
        }
        return $query->getResultArray();
    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/find_available_location/1206C.3.2.1.1
    // https://dmsdev.pnl.gov/freezer/find_available_location/1206C.3.2.1.1
    function find_available_location($location) {
        $tmpl = <<<EOD
SELECT Location As tag, freezer, shelf, rack, row, col, comment,
       Container_Limit As limit, containers, available, status, id
FROM (
    SELECT Location, Freezer_Tag As Freezer, Shelf, Rack, Row, Col, Comment, Container_Limit, Containers,
           Available, Status, ID, Row_Number() Over (ORDER BY Freezer_Tag, Shelf, Rack, Row, Col) As RankValue
    FROM V_Material_Location_List_Report
    WHERE Location LIKE '@LOC@%' AND
          Available > 0 AND Status = 'Active'
    ) LookupQ
WHERE RankValue <= 10
EOD;
        $sql = str_replace("@LOC@", $location, $tmpl);
        $query = $this->db->query($sql);
        if (!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for available locations; see writable/logs/log-$currentTimestamp.php");
        }
        return $query->getResultArray();
    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/find_newest_containers
    // https://dmsdev.pnl.gov/freezer/find_newest_containers
    function find_newest_containers() {
        $sql = <<<EOD
SELECT Location As tag, freezer, shelf, rack, row, col, comment,
       Container_Limit As limit, containers, available, status, location_id As id, container, created
FROM (
    Select Location, Freezer_Tag AS Freezer, Shelf, Rack, Row, Col, Comment, 0 AS Container_Limit, 0 AS Containers,
           0 AS Available, Status, Location_ID,
           Container,
           Created, -- Container created
           Row_Number() Over (ORDER BY Created DESC) As RankValue
    FROM V_Material_Container_Locations
    WHERE Status = 'Active'         -- Container status
    ) LookupQ
WHERE RankValue <= 10
EOD;
        $query = $this->db->query($sql);
        if (!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new \Exception("Error querying database for newest containers; see writable/logs/log-$currentTimestamp.php");
        }
        return $query->getResultArray();
    }
}
?>
