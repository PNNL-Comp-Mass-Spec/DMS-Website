<?php
class Freezer_model extends CI_Model {

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
	// --------------------------------------------------------------------
	function get_freezers()
	{
		$sql = <<<EOD
SELECT 
	ML.Tag, ML.Freezer, ML.Shelf, ML.Rack, ML.Row, ML.Col, ML.Barcode, ML.Comment, ML.Container_Limit AS Limit, 
	COUNT(MC.ID) AS Containers, ML.Container_Limit - COUNT(MC.ID) AS Available, ML.Status, ML.ID
FROM dbo.T_Material_Locations ML
	LEFT OUTER JOIN dbo.T_Material_Containers MC ON ML.ID = MC.Location_ID
WHERE (Shelf = 'na') AND NOT Freezer = 'na'
GROUP BY ML.ID, ML.Freezer, ML.Shelf, ML.Rack, ML.Row, ML.Barcode, ML.Comment, ML.Tag,  ML.Col, ML.Status, ML.Container_Limit
EOD;
		$query = $this->db->query($sql);
		if(!$query) {
			throw new Exception("Error querying database");
		}
		return $query->result_array();
	}
	// --------------------------------------------------------------------
	function get_locations($Type, $Freezer, $Shelf, $Rack, $Row, $Col)
	{		
		$sql = <<<EOD
SELECT 
	ML.Tag, ML.Freezer, ML.Shelf, ML.Rack, ML.Row, ML.Col, ML.Barcode, ML.Comment, ML.Container_Limit AS Limit, 
	COUNT(MC.ID) AS Containers, ML.Container_Limit - COUNT(MC.ID) AS Available, ML.Status, ML.ID
FROM dbo.T_Material_Locations ML
	LEFT OUTER JOIN dbo.T_Material_Containers MC ON ML.ID = MC.Location_ID
EOD;
		switch($Type) {
			case 'Shelf':
				$sql .= " WHERE Freezer = '$Freezer' AND Rack = 'na' AND NOT Shelf = 'na' ";
				break;
			case 'Rack':
				$sql .= " WHERE Freezer = '$Freezer' AND Shelf = '$Shelf' AND Row = 'na' AND NOT Rack = 'na'";
				break;
			case 'Row':
				$sql .= " WHERE Freezer = '$Freezer' AND Shelf = '$Shelf' AND Rack = '$Rack'  AND  Col = 'na' AND NOT ROW = 'na'";
				break;
			case 'Col':
				$sql .= " WHERE Freezer = '$Freezer' AND Shelf = '$Shelf' AND Rack = '$Rack'  AND  Row = '$Row' AND NOT Col = 'na'";		
				break;
			case 'Tag':
				$sql .= "  WHERE ML.Tag IN ($Freezer)";		
				break;
		}
		$sql .= " GROUP BY ML.ID, ML.Freezer, ML.Shelf, ML.Rack, ML.Row, ML.Barcode, ML.Comment, ML.Tag,  ML.Col, ML.Status, ML.Container_Limit";
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
			throw new Exception("Error querying database");
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
			throw new Exception("Error querying database");
		}
		return $query->result_array();
	}

	// --------------------------------------------------------------------
	function build_freezer_location_list($Type, $locations)
	{
		$items = array();
		foreach($locations as $entry) {
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
			//			$obj->hideCheckbox = true;
	
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
			throw new Exception("Error querying database");
		}
		return $query->result_array();
	}
 
}
?>