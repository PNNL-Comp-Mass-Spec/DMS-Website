<?php
require("Base_controller.php");

class Gen extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		session_start();
		$this->load->helper(array('url', 'string'));
		$this->color_code = $this->config->item('version_color_code');
		$this->page_menu_root = ($this->config->item('page_menu_root')) ? $this->config->item('page_menu_root') : "page_menu" ;
	}
	
	/**
	 * Display the home page, with the side menu in a frame
	 * http://dms2.pnl.gov/gen/
	 * http://dms2.pnl.gov/gen/index
	 */
	function index()
	{
		$page = $this->input->post('page');
		$pageToShow = ($page != '')?$page:site_url()."gen/welcome";
		
		$data['page_url'] = $pageToShow;
		$data['side_menu_url'] = site_url()."gen/side_menu";

		$this->load->vars($data);
		$this->load->view('top_level_frames');
	}
	
	/**
	 * Display the current configuration
	 * http://dms2.pnl.gov/gen/config
	 */
	function config()
	{
		echo("<li>Environment:".ENVIRONMENT . "\n");
//		$this->config->load('database', TRUE);
		$this->load->database();

		$version = $this->color_code = $this->config->item('version_label');
		echo("<li>version:$version\n");

		$archiveRoot = $this->color_code = $this->config->item('file_attachment_archive_root_path');
		echo("<li>file attachment path:$archiveRoot\n");

		$dbName = $this->db->database;
		echo("<li>database:$dbName\n");

		$userName = $this->db->username;
		echo("<li>user:$userName\n");
	}
	
	/**
	 * Create the menus
	 * @param type $title
	 * @param type $sub_view_name
	 * @param type $splash_view_name
	 * @param type $menu_config_db
	 * @param type $menu_section_table
	 * @param type $menu_item_table
	 */
	function _page_menu(
				$title,
				$sub_view_name,
				$splash_view_name = '',
				$menu_config_db = "dms_menu.db",
				$menu_section_table = "home_menu_sections",
				$menu_item_table = "home_menu_items"
	)
	{
		$this->load->model('dms_menu', 'menu', TRUE);
		$this->load->helper(array('form', 'user', 'menu', 'dms_search'));

		// labelling information for view
		$data['title'] = $title;
		$data['heading'] = $title;

		// nav_bar setup
		$this->load->model('dms_menu', 'menu', TRUE);
		$data['nav_bar_menu_items']= get_nav_bar_menu_items('');

		// home page menu sections
		$defs = $this->menu->get_section_menu_def($menu_config_db, $menu_section_table, $menu_item_table);
		$data['qs_section_defs'] = $defs;

		// which sub view to load?
		$data['sub_view_name'] = $sub_view_name;
		$data['splash_view_name'] = ($splash_view_name)?$splash_view_name:'splash_default';
		$data['page_menu_root'] = $this->page_menu_root;

		$this->load->vars($data);
		$this->load->view($this->page_menu_root . '/page_menu');
	}

	/**
	 * Create the specified menu
	 * @param string $menu_name
	 */
	function page_menu($menu_name)
	{
		$sub_view_name ='sub_view_'.$menu_name;
		$menu_config_db = "dms_".$menu_name."_menu.db";
		// $menu_section_table = "home_menu_sections";
		// $menu_item_table = "home_menu_items";
		$title = "PRISM DMS " . ucwords($menu_name);

		$this->_page_menu($title, $sub_view_name, '', $menu_config_db);
	}

	// --------------------------------------------------------------------
	function custom()
	{
		echo "Go <a href='".site_url()."gen/cart/fly'>here</a> for page layout using 'flying' menu sections\n";
		echo "<br><br>";
		echo "Go <a href='".site_url()."gen/cart/sections'>here</a> for page layout using static menu sections \n";
	}

	// --------------------------------------------------------------------
	function admin($layout = 'fly')
	{
		$selectedLayout = $layout == 'fly' ? 'sub_view_fly_menus' : 'sub_view_section_menus' ;
		$this->_page_menu("DMS Admin", $selectedLayout, 'splash_admin', "dms_admin_menu.db");
	}

	/**
	 * Display the home page
	 * http://dms2.pnl.gov/gen/welcome
	 */
	function welcome()
	{
		$this->_page_menu("Welcome to DMS", "sub_view_welcome");
	}


	/**
	 * Construct the side menu
	 * http://dms2.pnl.gov/gen/side_menu
	 */
	function side_menu()
	{
		$this->load->helper(array('menu', 'dms_search'));
		$this->load->model('dms_menu', 'menu', TRUE);
		$this->load->view('menu_panel');
	}
	
	/**
	 * Return the side menu items a json
	 * http://dms2.pnl.gov/gen/side_menu_objects
	 */
	function side_menu_objects()
	{
		$this->load->helper(array('menu', 'dms_search'));
		$this->load->model('dms_menu', 'menu', TRUE);

		$menu_def = $this->menu->get_menu_def("dms_menu.db", "menu_def");
		$items = build_side_menu_object_tree($menu_def, '', '');
		echo json_encode($items);
	}
	
	/**
	 * Show session information
	 * http://dms2.pnl.gov/gen/show_session
	 */
	function show_session()
	{
		// echo Base_controller::var_dump_ex($_SESSION);

		echo "Session ID: ". session_id  () . "<hr />";
		echo "SID: ". SID . "<hr />";

		foreach($_SESSION as $k => $v) {
			echo $k . "<br />" . serialize($v) . "<hr />";
		}
	}

	/**
	 * Show configuration info
	 * http://dms2.pnl.gov/gen/info
	 */
	function info()
	{
		// If the authentication type is basic, PHP_AUTH_PW will show the password as clear text
		// The following checks for this and obfuscates the password if needed
		$serverVars = $_SERVER;
		$savedPassword = "";
		
		if (array_key_exists ('PHP_AUTH_PW' , $serverVars )) {
			$savedPassword = $serverVars["PHP_AUTH_PW"];
			$_SERVER["PHP_AUTH_PW"] = "******** (masked by application/controllers/Gen.php)";
		}		
		
		echo phpinfo();
		
		Base_controller::var_dump_ex($_SERVER);
		
		if (strlen($savedPassword) > 0) {
			$_SERVER["PHP_AUTH_PW"] = $savedPassword;
		}

	}

	/**
	 * Read the restricted actions defined in the master_authorization SQLite database
	 * Display the results in an HTML table
	 * http://dms2.pnl.gov/gen/auth
	 */
	function auth()
	{
		// load the authorization model
		$this->load->model('dms_authorization', 'auth');
		$rows = $this->auth->get_master_restriction_list();

		$this->load->library('table');
		$tmpl = array ('table_open' => '<table border="1" cellpadding="4" cellspacing="0">');
		$this->table->set_template($tmpl);

		$this->table->set_heading('Page Family', 'Action', 'Restrictions');

		foreach($rows as $row) {
			array_shift($row); // get rid of id column
			$this->table->add_row($row);
		}

		echo $this->table->generate();
	}

	/**
	 * Show statistics on datasets, experiments, etc.
	 * http://dms2.pnl.gov/gen/stats
	 */
	function stats()
	{
		$this->load->model('dms_statistics', 'model', TRUE);
		$this->load->helper(array('form', 'user', 'dms_stats', 'dms_search', 'menu'));

		// nav_bar setup
		$this->load->model('dms_menu', 'menu', TRUE);
		$data['nav_bar_menu_items']= get_nav_bar_menu_items('Statistics');

		// labelling information for view
		$data['title'] = "DMS Statistics";
		$data['heading'] = $data['title'];

		$result = $this->model->get_stats();
		$data['results'] = $result;

		$this->load->vars($data);
		$this->load->view('special/statistics');

	}

}
