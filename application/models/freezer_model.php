<?php
class Freezer_model extends CI_Model {

	var $hierarchy = array(
		"Freezer" => "Shelf",
		"Shelf" => "Rack",
		"Rack" => "Row",
		"Row" => "Col",
		"Col" => "",
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
				$sql .= " WHERE     Freezer = '$Freezer' AND Shelf = '$Shelf' AND Rack = '$Rack'  AND  Row = '$Row' AND NOT Col = 'na'";		
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
	function build_freezer_location_list($Type, $locations)
	{
		$items = array();
		foreach($locations as $entry) {
			$name = $entry[$Type];
			$obj = new stdClass();
			$obj->title =  "$Type $name";
			$obj->isFolder = true;
			$obj->isLazy = true;
	
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
			$obj->hideCheckbox = true;
	
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
	
/*
	// --------------------------------------------------------------------
	function get_locations($freezer_spec, $shelf_spec,  $rack_spec) 
	{
		$this->load->database();

 		// labelling information for view
		$data['title'] = "Freezer";
		$data['heading'] = "Freezer";

		// navbar support
		$this->load->model('dms_menu', 'menu', TRUE);
		$data['nav_bar_menu_items']= get_nav_bar_menu_items('List_Report');

		// optional limits on what to include
		$freezer_spec = $this->uri->segment(3);
		$shelf_spec = $this->uri->segment(4);
		$rack_spec = $this->uri->segment(5);

		// populate array of storage locations
		$sql = "";
		$sql .= "SELECT Freezer, Shelf, Rack, Row, Col, Location, Available ";
		$sql .= "FROM V_Material_Locations_List_Report ";
		$sql .= "WHERE Status = 'Active' ";
		if($freezer_spec) {
			$sql .= "AND Freezer LIKE '%$freezer_spec%' ";
		}
		if($shelf_spec) {
			$sql .= "AND Shelf = '$shelf_spec' ";
		}
		if($rack_spec) {
			$sql .= "AND Rack = '$rack_spec' ";
		}
		$sql .= "ORDER BY Freezer, Shelf, Rack, Row, Col ";
		//
		$result = $this->db->query($sql);
		//
		if(!$result) {echo "Error loading location information"; return;}
		//
		$storage = array();
		$rows = $result->result_array();
	}
*/
 
}
?>