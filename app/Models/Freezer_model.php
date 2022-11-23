<?php
namespace App\Models;

use CodeIgniter\Model;

class Freezer_model extends Model {

    // Freezer name is column Freezer_Tag in tables T_Material_Freezers and T_Material_Locations in the database
    var $hierarchy = array(
        "Freezer" => "Shelf",
        "Shelf" => "Rack",
        "Rack" => "Row",
        "Row" => "Col",
        "Col" => "",
        "Tag" => "Tag"
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
     * @return type
     * @throws Exception
     */
    function get_freezers() {
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
     * @param type $Type Location type: Shelf, Rack, Row, Col, or Tag
     * @param type $Freezer Freezer name, but if $Type = 'Tag', this is location name
     * @param type $Shelf Shelf number (ignored if $Type is Shelf or Tag
     * @param type $Rack Rack number (ignored if $Type is Shelf, Rack, or Tag
     * @param type $Row Row number (only used if $Type is Col)
     * @return type
     */
    function get_locations($Type, $Freezer, $Shelf, $Rack, $Row) {
        $sql = <<<EOD
SELECT Location As tag, Freezer_Tag As freezer, shelf, rack, row, col, comment,
       Container_Limit As limit, containers, available, status, id
FROM V_Material_Location_List_Report
EOD;
        switch ($Type) {
            case 'Shelf':
                $sql .= " WHERE Freezer_Tag = '$Freezer' AND Rack = 'na' AND NOT Shelf = 'na' ";
                break;
            case 'Rack':
                $sql .= " WHERE Freezer_Tag = '$Freezer' AND Shelf = '$Shelf' AND Row = 'na' AND NOT Rack = 'na'";
                break;
            case 'Row':
                $sql .= " WHERE Freezer_Tag = '$Freezer' AND Shelf = '$Shelf' AND Rack = '$Rack'  AND  Col = 'na' AND NOT ROW = 'na'";
                break;
            case 'Col':
                $sql .= " WHERE Freezer_Tag = '$Freezer' AND Shelf = '$Shelf' AND Rack = '$Rack'  AND  Row = '$Row' AND NOT Col = 'na'";
                break;
            case 'Tag':
                $sql .= "  WHERE ML.Tag IN ($Freezer)";
                break;
        }
        $sql .= " GROUP BY ML.ID, ML.Freezer_Tag, ML.Shelf, ML.Rack, ML.Row, ML.Barcode, ML.Comment, ML.Tag,  ML.Col, ML.Status, ML.Container_Limit";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    // --------------------------------------------------------------------
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
        $type = "Freezer";
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
            $info->Barcode = "";
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
                $name = $entry[$Type];
                $obj = new \stdClass();
                $obj->title = "$Type $name";
                $obj->folder = true;
                $obj->lazy = true;
                $obj->key = $entry['Tag'];

                $info = new \stdClass();
                $info->Name = $name;
                $info->Type = $Type;
                $info->ID = $entry['ID'];
                $info->Tag = $entry['Tag'];
                $info->Freezer = $entry['Freezer'];
                $info->Shelf = $entry['Shelf'];
                $info->Rack = $entry['Rack'];
                $info->Row = $entry['Row'];
                $info->Col = $entry['Col'];
                $info->Status = $entry['Status'];
                $info->Barcode = $entry['Barcode'];
                $info->Comment = $entry['Comment'];
                $info->Limit = $entry['Limit'];
                $info->Containers = $entry['Containers'];
                $info->Available = $entry['Available'];
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
            $info->Container = $entry['Container'];
            $info->ContainerType = $entry['Type'];
            $info->Location = $entry['Location'];
            $info->Items = $entry['Items'];
            $info->Files = $entry['Files'];
            $info->Comment = $entry['Comment'];
            //$info->Barcode = $entry['Barcode'];
            $info->Created = $entry['Created'];
            $info->Campaigns = $entry['Campaigns'];
            $info->Researcher = $entry['Researcher'];
            $info->ID = $entry['ID'];
            $obj->info = $info;

            $items[] = $obj;
        }
        return $items;
    }

    // --------------------------------------------------------------------
    function build_material_item_list($material_items) {
        $items = array();
        foreach ($material_items as $entry) {
            $name = "${entry['Item_Type']} ${entry['Item']}";
            $obj = new \stdClass();
            $obj->title = $name;
            $obj->folder = false;
            $obj->lazy = false;
            $obj->hideCheckbox = true;

            $info = new \stdClass();
            $info->Name = $name;
            $info->Type = "Material";
            $info->Item_Type = $entry['Item_Type'];
            $info->Item = $entry['Item'];
            $info->ID = $entry['ID'];
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
