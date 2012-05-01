<?php
//require("base_controller.php");

class lc_cart_controller_graphic extends Controller {

	var $my_tag = "lc_cart_controller_graphic";

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		session_start();
		$this->load->helper(array('url', 'string', 'lc_cart_component', 'user'));

		$this->color_code = $this->config->item('version_color_code');
		$this->help_page_link = $this->config->item('pwiki');
		$this->help_page_link .= $this->config->item('wikiHelpLinkPrefix');
	}
	// --------------------------------------------------------------------
	function index()
	{
	}
	// --------------------------------------------------------------------
	function cart()
	{
		$this->load->helper(array('user', 'dms_search', 'menu'));

 		// labelling information for view
		$data['title'] = "LC Cart Configuration";
		$data['heading'] = "LC Cart Component Maintenance";

		// nav_bar setup
		$this->load->model('dms_menu', 'menu', TRUE);
		$data['nav_bar_menu_items']= get_nav_bar_menu_items('Cart_Graphic');

		// cart name
		$cart = $this->uri->segment(3); // "Owl"
		$data["cart_name"] = $cart; //$this->uri->segment(3)

		// get component inventory for given cart
		$sql = "SELECT * FROM V_LC_Cart_Component_Positions WHERE Cart = '". $cart . "'";
		$this->load->database();
		$result = $this->db->query($sql);
		//
		if(!$result) {
			echo "No results found for cart";
			return;
		}

		// package data for view, indexed by position
		$d = array();
		foreach($result->result_array() as $item) {
			$d[$item["Position"]] = $item;
		}
		$data["result"] = $d;

		$this->load->vars($data);
		$this->load->view('special/lc_cart_controller_graphic');
	}
	// --------------------------------------------------------------------
	// returns content of cart cell at specified location
	// (AJAX call)
	function cell()
	{

		// cart name
		$posID = $this->uri->segment(3);

		// get component inventory for given cart
		$sql = "SELECT * FROM V_LC_Cart_Component_Positions WHERE ID = $posID";
		$this->load->database();
		$result = $this->db->query($sql);
		//
		if(!$result) {
			echo "No results found for position";
			return;
		}

		echo make_installed_position_cell_contents(current($result->result_array()));
	}

	// --------------------------------------------------------------------
	// returns information about specified component (or nothing if it doesn't exist)
	// (AJAX call)
	function component_check()
	{
		// type and serial number of component
		$type = $this->uri->segment(3);
		$sn = $this->uri->segment(4);

		// get component information
		$sql = "SELECT ID, Status FROM  V_LC_Cart_Components_Detail_Report WHERE (Type = '$type') AND ([Serial Number] = '$sn') ";
		$this->load->database();
		$result = $this->db->query($sql);
		//
		if(!$result) {
			echo "Error";
			return;
		}
		$row = $result->row_array();
		if(count($row)==0) {
			echo 0;
		} else {
			echo $row["ID"];
		}
	}
}
?>