<?php
require("base_controller.php");

class Freezer extends Base_controller {

	var $my_tag = "freezer";


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		session_start();
		$this->load->helper(array('url', 'string', 'user'));

		$this->color_code = $this->config->item('version_color_code');
		$this->help_page_link = $this->config->item('pwiki');
		$this->help_page_link .= $this->config->item('wikiHelpLinkPrefix');
	}
	// --------------------------------------------------------------------
	function index()
	{
		echo "howdy";
	}

	// --------------------------------------------------------------------
	function tree()
	{
		$this->load->helper(array('menu', 'dms_search'));
		$this->load->model('dms_menu', 'menu', TRUE);

		$data['nav_bar_menu_items']= $this->get_basic_nav_bar_items();
		
		$this->load->vars($data);
		$this->load->view('special/freezer_tree');
	}

	// --------------------------------------------------------------------
	// AJAX
	function get_freezers()
	{
		$this->load->model('freezer_model', 'freezer', TRUE);

		$frzrs = $this->freezer->get_freezers();
		$items = $this->freezer->build_freezer_location_list('Freezer', $frzrs);
		echo json_encode($items);
	}
	// --------------------------------------------------------------------
	// AJAX
	function get_locations()
	{
		$this->load->model('freezer_model', 'freezer', TRUE);

		$Type = $this->input->get_post('Type');
		$Freezer = $this->input->get_post('Freezer');
		$Shelf = $this->input->get_post('Shelf');
		$Rack = $this->input->get_post('Rack');
		$Row = $this->input->get_post('Row');
		$Col = $this->input->get_post('Col');

		$sub_type = $this->freezer->get_sub_location_type($Type);
		$frzrs = $this->freezer->get_locations($sub_type, $Freezer, $Shelf, $Rack, $Row, $Col);
		$items = $this->freezer->build_freezer_location_list($sub_type, $frzrs);
		echo json_encode($items);
	}
	// --------------------------------------------------------------------
	// AJAX
	function get_containers()
	{
		$this->load->model('freezer_model', 'freezer', TRUE);

		$location = $this->input->get_post('Location');

		$containers = $this->freezer->get_containers($location);
		$items = $this->freezer->build_container_list($containers);
		echo json_encode($items);
	}
	// --------------------------------------------------------------------
	// AJAX
	function find_container()
	{
		$this->load->model('freezer_model', 'freezer', TRUE);

		$container = $this->input->get_post('Container');

		$containers = $this->freezer->find_container($container);
		$items = $this->freezer->build_container_list($containers);
		echo json_encode($items);
	}
	// --------------------------------------------------------------------
	// AJAX
	function find_location()
	{
		$this->load->model('freezer_model', 'freezer', TRUE);

		$location = $this->input->get_post('Location');

		$locations = $this->freezer->find_location($location);
		$items = $this->freezer->build_freezer_location_list('', $locations);
		echo json_encode($items);
	}
	// --------------------------------------------------------------------
	// AJAX
	function find_available_location()
	{
		$this->load->model('freezer_model', 'freezer', TRUE);

		$location = $this->input->get_post('Location');

		$locations = $this->freezer->find_available_location($location);
		$items = $this->freezer->build_freezer_location_list('', $locations);
		echo json_encode($items);
	}
	// --------------------------------------------------------------------
	function test()
	{
		$this->load->model('freezer_model', 'freezer', TRUE);
		$testLocs = array(
			"80B.na.na.na.na",
			"80B.1.na.na.na",
			"80B.1.2.na.na",
			"80B.1.2.1.na",			
			"80B.1.2.1.2",
		);
		foreach($testLocs as $location) {
			$locations = $this->freezer->find_location($location);
			echo $location . "=>" .$this->freezer->get_location_type($locations[0]) ."\n";
		}
	}
		
	// --------------------------------------------------------------------
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	function import_shelf()
	{
		$this->load->helper('form');
		echo "<h2>Import Freezer Shelf Contents From Text</h2>";


		// get input text and allow for it to be empty
		// without causing error
		$str = '';
		if(array_key_exists("input_text", $_POST)) {
			$str = $_POST["input_text"];
		}

		// convert blob of text into an array of arrays
		// where the inner array is a grid of locations and contents for a rack
		// and the outer array represents racks
		$racks = array();
		$grid = array();
		if($str) {
			// split blob of input text into an array of lines
			$str = str_replace(chr(13), '', $str); // clean out lf
			$lines = explode(chr(10), $str); // break on cr

			$freezer = ''; $shelf = ''; $rack = ''; $row = 0;
			foreach($lines as $line) {
				// line with only whitespace means new rack
				if(preg_match('/^\s*$/', $line)) {
					$row = 0; // reset row count
					$racks[] = $grid; // add existing grid to rack array
					$grid = array(); // new grid array

				} else {
					// break line into fields
					$a = preg_split('/\t/', $line);

					// first row contains freezer, shelf, and rack
					// information
					if($row==0) {
						// freezer, shelf, rack
						list($freezer, $shelf, $rack) = explode(',', $a[0]);
						$freezer = trim(preg_replace('/Freezer\s+/i', '', $freezer));
						$shelf = trim(preg_replace('/Shelf\s+/i', '', $shelf));
						$rack = trim(preg_replace('/Rack\s+/i', '', $rack));
						$fsr = "80$freezer.$shelf.$rack";
					}
					if($row>0 && $row<5) {
						// row and columns in rack
						for($col=0;$col<count($a);$col++) {
							$c = $col+1;
							$loc = $fsr.".$row.$c";
							$grid[$row][$col]= array($loc, $a[$col]);
						}
					}
					$row++;
				}
			}
		}

		echo "<hr>";

		// generate SQL for
		$sq = '';
		foreach($racks as $grid) {
			for($r=1;$r<=count($grid);$r++) {
				for($i=0;$i<count($grid[$r]);$i++) {

					// get location and comment from parsed results
					$loc = $grid[$r][$i][0];
					$comment = $grid[$r][$i][1];

					// clean up problem characters
					$comment = str_replace("'", '`', $comment);

					// make SQL loading rack contents
					switch($comment) {
						case "Empty";
							// do nothing
							break;

						case "";
							// set location inactive
							$sq .= "UPDATE T_Material_Locations SET  Status = 'Inactive' WHERE  Tag = '$loc';<br>\n";
							break;

						default:
							$sq .= "exec AddUpdateMaterialContainer '(generate name)', 'Box', '$loc', '$comment', '', 'add', @message, 'D3J410';<br>\n";
							break;
					}
				}
			}
		$sq .=  "<br>";
		$sq .=  "-- declare @message varchar(512)<br>";
		}


		// create textarea in form to receive input
		// text from user
		echo form_open("freezer/import_shelf");
		$data = array(
              'name'        => 'input_text',
              'id'          => 'it',
			  'value'       => $str,
            );
		echo form_textarea($data);
		echo form_input('command', 'check');
		echo form_submit("Go", "Load");
		echo form_close();

		// dump rack contents in table format
		//
		$this->load->library('table');
		foreach($racks as $grid) {
			$tmpl = array ( 'table_open'  => '<table border="1" cellpadding="2" cellspacing="1" class="mytable">' );
			$this->table->set_template($tmpl);
			foreach($grid as $row) {
				$a = array();
				for($i=0;$i<count($row);$i++) {
					$s = '';
					for($f=0;$f<count($row[$i]);$f++) {
						$s .= "<div>".$row[$i][$f]."</div>";
					}
					$a[$i] = $s; //"<div style='font-weight:bold;'>".$row[$i][0]."</div>"."<div>".$row[$i][1]."</div>";
				}
				$this->table->add_row($a);
			}
			echo $this->table->generate();
			echo "<hr>";
			$this->table->clear();
		}

		// dump SQL
		echo "<hr>";
		echo $sq;
		echo "<br>";

	}

	// --------------------------------------------------------------------
	// show freezer
	function junk()
	{
		$this->load->helper(array('url', 'string'));
		$this->load->library('table');

		// optional limits
		$freezer = $this->uri->segment(3);
		$shelf = $this->uri->segment(4);
		$rack = $this->uri->segment(5);

		// type and serial number of component
		$freezer = $this->uri->segment(3);

		echo "<h2>Freezer:$freezer </h2>";

		// get  information
		//Location, Freezer, Shelf, Rack, Row, Col, Barcode, Comment, Limit, Containers, Status
		$sql = "";
		$sql .= "SELECT DISTINCT Shelf, Rack ";
		$sql .= "FROM  V_Material_Locations_List_Report ";
		$sql .= "WHERE ";
		$sql .= "(Freezer like '%$freezer%') ";
//echo $sql;
//echo "<hr>";
		//
		$this->load->database();
		$result = $this->db->query($sql);
		//
		if(!$result) {
			echo "Error";
			return;
		}

		$grid = array();
		$rows = $result->result_array();
		foreach($rows as $row) {
			$shelf = $row['Shelf'];
			$rack = $row["Rack"];
			if($rack == 'na') {
				$a = '';
			} else {
				$hr = site_url()."freezer/show_rack/".$freezer."/".$shelf."/".$rack;
				$a = "<a href='$hr'>"."S:".$shelf.",R:".$rack."</a>";
			}
			$grid[$shelf][$rack] = $a;
		}

		// display results in table
		//
		$tmpl = array ( 'table_open'  => '<table border="1" cellpadding="2" cellspacing="1" class="mytable">' );
		$this->table->set_template($tmpl);
		$this->table->set_heading("", "Rack 1", "Rack 2", "Rack 3", "Rack 4", "Rack 5", "Rack 6", "No Rack");
		for($s=1;$s<=5;$s++) {
			$label = "<span style='font-weight:bold;'>Shelf $s</span>";
			array_unshift($grid[$s], $label);
			$this->table->add_row($grid[$s]);
		}
		echo $this->table->generate();

	}

	// --------------------------------------------------------------------
	function show()
	{
		$this->load->helper(array('freezer_helper', 'url', 'string', 'user', 'dms_search', 'menu'));
		$this->load->library('table');
		$this->load->database();

 		// labelling information for view
		$data['title'] = "Freezer Map";
		$data['heading'] = "Freezer Map";

		// navbar support
		$this->load->model('dms_menu', 'menu', TRUE);
		$data['nav_bar_menu_items']= get_nav_bar_menu_items('Detail_Reports');

		// populate array of storage locations
		$sql = "";
		$sql .= "SELECT DISTINCT Location, Freezer, CASE WHEN CHARINDEX('20Met.D', Location) = 0 THEN Shelf ELSE 'D-' + Shelf END AS Shelf, Rack ";
		$sql .= "FROM V_Material_Locations_List_Report ";
		$sql .= "WHERE Status = 'Active' ";
		$sql .= "ORDER BY Location, Freezer, Shelf, Rack";
		//
		$result = $this->db->query($sql);
		//
		if(!$result) {echo "Error loading location information"; return;}
		//
		$storage = array();
		$rows = $result->result_array();
		foreach($rows as $r) {
			$storage[$r['Freezer']][$r['Shelf']][$r['Rack']] = '' ;
		}

		$data['content'] = '';
		// show locations map in tables
		$fc_url = site_url()."freezer/contents";
		$tmpl = array (
			'table_open'  => '<table border="1" cellpadding="2" cellspacing="1" class="EPag">',
			'heading_cell_start' => '<th class="block_header" colspan="7">'
		);
		$this->table->set_template($tmpl);
		//
		foreach($storage as $freezer => $f) {
			$c_url = "<a href='$fc_url/$freezer'>Contents</a>";
			$this->table->set_heading("Freezer:$freezer $c_url");
			foreach($f as $shelf => $s) {
				$tr = array();
				$s_url = "<a href='$fc_url/$freezer/$shelf'>Contents</a>";
				$tr[] = "<span style='font-weight:bold;'> ". "Shelf:".$shelf . " &nbsp; " . $s_url . "</span>";
				foreach($s as $rack => $rk)	{
					if($rack == 'na') {

					} else {
						$r_url = "<a href='$fc_url/$freezer/$shelf/$rack'>Contents</a>";
						$tr[] = "Rack:".$rack . " &nbsp; " . $r_url;
					}
				}
				$this->table->add_row($tr);
			}
			$data['content'] .= $this->table->generate() . '<br>';
			$this->table->clear();
		}
		$this->load->vars($data);	
		$this->load->view('basic');
		
	}

	// --------------------------------------------------------------------
	function contents()
	{
		$this->load->helper(array('freezer_helper', 'url', 'string', 'user', 'dms_search', 'menu'));
		$this->load->library('table');
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
		foreach($rows as $r) {
			$storage[$r['Freezer']][$r['Shelf']][$r['Rack']][$r['Row']][$r['Col']] = array( 'Location' => $r['Location'], 'Available' => $r['Available']) ;
		}

		// populate array of location contents
		$sql = "";
		$sql .= "SELECT T_Material_Containers.Tag AS Container, T_Material_Locations.Tag AS Location, T_Material_Containers.Comment ";
		$sql .= "FROM T_Material_Containers ";
		$sql .= "  INNER JOIN T_Material_Locations ON T_Material_Containers.Location_ID = T_Material_Locations.ID ";
		if($freezer_spec) {
			$sql .= "WHERE Freezer LIKE '%$freezer_spec%' ";
		}
		if($shelf_spec) {
			$sql .= "AND (Shelf = '$shelf_spec') ";
		}
		if($rack_spec) {
			$sql .= "AND (Rack = '$rack_spec') ";
		}
		//
		$result = $this->db->query($sql);
		//
		if(!$result) {echo "Error loading container information";return;}
		//
		$contents = array();
		$rows = $result->result_array();
		foreach($rows as $r) {
			$contents[$r['Location']][] = array( 'Container' => $r['Container'], 'Comment' => $r['Comment']);
		}

		$data['storage'] = $storage;
		$data['contents'] = $contents;

		$this->load->vars($data);
		$this->load->view('special/freezer');
	}

}
?>