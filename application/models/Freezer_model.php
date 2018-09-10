<?php
class Freezer_model extends CI_Model {

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
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    // --------------------------------------------------------------------
    function check_connection()
    {
        return true;
    }
    
    // --------------------------------------------------------------------
    function get_sub_location_type($type) 
    {
        return $this->hierarchy[$type];
    }   
    
    /**
     * https://dms2.pnl.gov/freezer/get_freezers
     * @return type
     * @throws Exception
     */
    function get_freezers()
    {
        $sql = <<<EOD
SELECT 
    ML.Tag, ML.Freezer_Tag AS Freezer, ML.Shelf, ML.Rack, ML.Row, ML.Col, ML.Barcode, ML.Comment, ML.Container_Limit AS Limit, 
    COUNT(MC.ID) AS Containers, ML.Container_Limit - COUNT(MC.ID) AS Available, ML.Status, ML.ID
FROM dbo.T_Material_Locations ML
    LEFT OUTER JOIN dbo.T_Material_Containers MC ON ML.ID = MC.Location_ID
WHERE (Shelf = 'na') AND NOT Freezer_Tag = 'na'
GROUP BY ML.ID, ML.Freezer_Tag, ML.Shelf, ML.Rack, ML.Row, ML.Barcode, ML.Comment, ML.Tag,  ML.Col, ML.Status, ML.Container_Limit
EOD;
        $query = $this->db->query($sql);
        if(!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new Exception ("Error querying database for freezers; see application/logs/log-$currentTimestamp.php");
        }
        return $query->result_array();
    }

    /**
     * https://dms2.pnl.gov/freezer/get_locations/Tag/1206C.3.2.1.1
     * @param type $Type Location type: Shelf, Rack, Row, Col, or Tag
     * @param type $Freezer Freezer name
     * @param type $Shelf Shelf number (ignored if $Type is Shelf or Tag
     * @param type $Rack Rack number (ignored if $Type is Shelf, Rack, or Tag
     * @param type $Row Row number (only used if $Type is Col)
     * @return type
     */
    function get_locations($Type, $Freezer, $Shelf, $Rack, $Row)
    {       
        $sql = <<<EOD
SELECT 
    ML.Tag, ML.Freezer_Tag AS Freezer, ML.Shelf, ML.Rack, ML.Row, ML.Col, ML.Barcode, ML.Comment, ML.Container_Limit AS Limit, 
    COUNT(MC.ID) AS Containers, ML.Container_Limit - COUNT(MC.ID) AS Available, ML.Status, ML.ID
FROM dbo.T_Material_Locations ML
    LEFT OUTER JOIN dbo.T_Material_Containers MC ON ML.ID = MC.Location_ID
EOD;
        switch($Type) {
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
        return $query->result_array();      
    }

    // --------------------------------------------------------------------
    function get_containers($location)
    {
        $sql = <<<EOD
SELECT  Container, Type, Location, Items, Files, Comment, Action, Barcode, Created, Campaigns, Researcher, #ID AS ID
FROM V_Material_Containers_List_Report
EOD;
        $sql .= " WHERE Location = '$location'";
        $query = $this->db->query($sql);
        if(!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new Exception ("Error querying database for containers; see application/logs/log-$currentTimestamp.php");
        }
        return $query->result_array();
    }

    // --------------------------------------------------------------------
    function get_material($container)
    {
        $sql = <<<EOD
SELECT Item_Type, Item, ID
FROM [DMS5_T3].[dbo].[V_Material_Items_List_Report]
EOD;
        $sql .= " WHERE Container = '$container'";
        $query = $this->db->query($sql);
        if(!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new Exception ("Error querying database for material items; see application/logs/log-$currentTimestamp.php");
        }
        return $query->result_array();
    }

    // --------------------------------------------------------------------
    function get_location_type($location) 
    {
        $type = "Freezer";
        $locs = array_keys($this->hierarchy);
        array_pop($locs);
        foreach($locs as $loc) {
            $type = $loc;
            if(!$this->hierarchy[$loc]) {
                break;
            }
            if($location[$this->hierarchy[$loc]] == "na") {
                break;
            }
        }
        return $type;
    }

    // --------------------------------------------------------------------
    function build_freezer_location_list($Type, $locations)
    {
        $items = array();
        
        if (!$this->cu->check_access('operation', false)) {
            // User does not have permission to update items on this page
            // Return some dummy values

            $obj = new stdClass();
            $obj->title =  "Access denied: cannot update";
            $obj->isFolder = true;
            $obj->isLazy = true;
            $obj->key = "000";

            $info = new stdClass();
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
    
            foreach($locations as $entry) {
                if(!$Type) {
                    $Type = $this->get_location_type($entry);
                }
                $name = $entry[$Type];
                $obj = new stdClass();
                $obj->title =  "$Type $name";
                $obj->isFolder = true;
                $obj->isLazy = true;
                $obj->key = $entry['Tag'];
        
                $info = new stdClass();
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
    function build_container_list($containers)
    {
        $items = array();
        foreach($containers as $entry) {
            $name = $entry["Container"];
            $obj = new stdClass();
            $obj->title =  $name;
            $obj->isFolder = false;
            $obj->isLazy = true;
            $obj->key = $name;
            //          $obj->hideCheckbox = true;
    
            $info = new stdClass();
            $info->Name = $name;            
            $info->Type = "Container";
            $info->Container = $entry['Container'];
            $info->ContainerType = $entry['Type'];
            $info->Location = $entry['Location'];
            $info->Items = $entry['Items'];
            $info->Files = $entry['Files'];
            $info->Comment = $entry['Comment'];
            $info->Barcode = $entry['Barcode'];
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
    function build_material_item_list($material_items)
    {
        $items = array();
        foreach($material_items as $entry) {
            $name = "${entry['Item_Type']} ${entry['Item']}";
            $obj = new stdClass();
            $obj->title =  $name;
            $obj->isFolder = false;
            $obj->isLazy = false;
            $obj->hideCheckbox = true;
    
            $info = new stdClass();
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
    function find_container($container)
    {
        $sql = <<<EOD
SELECT  Container, Type, Location, Items, Files, Comment, Action, Barcode, Created, Campaigns, Researcher, #ID AS ID
FROM V_Material_Containers_List_Report
EOD;
        $sql .= " WHERE Container = '$container'";
        $query = $this->db->query($sql);
        if(!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new Exception ("Error querying database for container; see application/logs/log-$currentTimestamp.php");
        }
        return $query->result_array();
    }

    // --------------------------------------------------------------------
    function find_location($location)
    {
        $sql = <<<EOD
SELECT 
    ML.Tag, ML.Freezer_Tag AS Freezer, ML.Shelf, ML.Rack, ML.Row, ML.Col, ML.Barcode, ML.Comment, ML.Container_Limit AS Limit, 
    COUNT(MC.ID) AS Containers, ML.Container_Limit - COUNT(MC.ID) AS Available, ML.Status, ML.ID
FROM dbo.T_Material_Locations ML
    LEFT OUTER JOIN dbo.T_Material_Containers MC ON ML.ID = MC.Location_ID
EOD;
        $sql .= " WHERE ML.Tag = '$location'";
        $sql .= " GROUP BY ML.ID, ML.Freezer_Tag, ML.Shelf, ML.Rack, ML.Row, ML.Barcode, ML.Comment, ML.Tag,  ML.Col, ML.Status, ML.Container_Limit";
        $query = $this->db->query($sql);
        if(!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new Exception ("Error querying database for material location; see application/logs/log-$currentTimestamp.php");
        }
        return $query->result_array();
    }

    // --------------------------------------------------------------------
    function find_available_location($location)
    {
        $tmpl = <<<EOD
SELECT TOP (10)
ML.Tag, ML.Freezer_Tag AS Freezer, ML.Shelf, ML.Rack, ML.Row, ML.Col, ML.Barcode, ML.Comment, ML.Container_Limit AS Limit, COUNT(MC.ID) AS Containers, 
ML.Container_Limit - COUNT(MC.ID) AS Available, ML.Status, ML.ID
FROM 
T_Material_Locations AS ML LEFT OUTER JOIN
T_Material_Containers AS MC ON ML.ID = MC.Location_ID
WHERE (ML.Tag LIKE '@LOC@%')
GROUP BY ML.ID, ML.Freezer_Tag, ML.Shelf, ML.Rack, ML.Row, ML.Barcode, ML.Comment, ML.Tag, ML.Col, ML.Status, ML.Container_Limit
HAVING (ML.Container_Limit - COUNT(MC.ID) > 0) AND (ML.Status = 'Active')
ORDER BY ML.Freezer_Tag, ML.Shelf, ML.Rack, ML.Row, ML.Col
EOD;
        $sql = str_replace ("@LOC@" , $location ,$tmpl );
        $query = $this->db->query($sql);
        if(!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new Exception ("Error querying database for available locations; see application/logs/log-$currentTimestamp.php");
        }
        return $query->result_array();
    }

    // --------------------------------------------------------------------
    function find_newest_containers()
    {
        $sql = <<<EOD
SELECT TOP(10)
ML.Tag, ML.Freezer_Tag AS Freezer, ML.Shelf, ML.Rack, ML.Row, ML.Col, ML.Barcode, ML.Comment, 0 AS Limit, 0 AS Containers, 
0 AS Available, ML.Status, ML.ID, MC.Created
FROM T_Material_Containers AS MC 
INNER JOIN T_Material_Locations AS ML ON ML.ID = MC.Location_ID
WHERE MC.Status = 'Active'
ORDER BY MC.Created DESC
EOD;
        $query = $this->db->query($sql);
        if(!$query) {
            $currentTimestamp = date("Y-m-d");
            throw new Exception ("Error querying database for newest containers; see application/logs/log-$currentTimestamp.php");
        }
        return $query->result_array();
    }
 
}
