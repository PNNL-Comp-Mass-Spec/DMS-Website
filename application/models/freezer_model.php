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
SELECT ID, Tag, Freezer, Shelf, Rack, Row, Col, Status, Barcode, Comment, Container_Limit
FROM T_Material_Locations
WHERE (Shelf = 'na') AND NOT Freezer = 'na'
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
SELECT ID, Tag, Freezer, Shelf, Rack, Row, Col, Status, Barcode, Comment, Container_Limit
FROM T_Material_Locations 
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
		$query = $this->db->query($sql);
		return $query->result_array();		
	}
	// --------------------------------------------------------------------
	function build_freezer_location_list($Type, $locations)
	{
		$items = array();
		foreach($locations as $entry) {
			$obj = new stdClass();
			$obj->title =  "$Type $entry[$Type] ${entry['Status']}";
			$obj->isFolder = true;
			$obj->isLazy = true;
			$obj->Type = $Type;
			$obj->ID = $entry['ID'];
			$obj->Tag = $entry['Tag'];
			$obj->Freezer = $entry['Freezer'];
			$obj->Shelf = $entry['Shelf'];
			$obj->Rack = $entry['Rack'];
			$obj->Row = $entry['Row'];
			$obj->Col = $entry['Col'];
			$obj->Status = $entry['Status'];
			$obj->Barcode = $entry['Barcode'];
			$obj->Comment = $entry['Comment'];
			$obj->Container_Limit = $entry['Container_Limit'];
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