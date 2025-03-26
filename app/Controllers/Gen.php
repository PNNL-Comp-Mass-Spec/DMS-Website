<?php
namespace App\Controllers;

use App\Controllers;

class Gen extends BaseController
{
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['url', 'text'];

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();

        //Ensure a session is initialized
        $session = \Config\Services::session();

        $this->config = config('App');
        $this->page_menu_root = ($this->config->page_menu_root) ? $this->config->page_menu_root : "page_menu" ;
	}

    /**
     * Display the home page, with the side menu in a frame
     * https://dms2.pnl.gov/gen/
     * https://dms2.pnl.gov/gen/index
     */
    function index()
    {
		// TODO: $page = $this->request->getPost('page');
        $page = '';
        $pageToShow = ($page != '')?$page:site_url('gen/welcome');

        $data['page_url'] = $pageToShow;
        $data['side_menu_url'] = site_url('gen/side_menu');

        echo view('top_level_frames', $data);
    }

    /**
     * Display the current configuration
     * https://dms2.pnl.gov/gen/config
     */
    function config()
    {
        echo("<li>Environment:".ENVIRONMENT . "\n");
//      $this->config->load('database', TRUE);
        $this->db = \Config\Database::connect();
        $this->updateSearchPath($this->db);

        $version = $this->config->version_label;
        echo("<li>version:$version\n");

        $archiveRoot = $this->config->file_attachment_archive_root_path;
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
        $this->menu = model('\\App\\Models\\Dms_menu');
        helper(['form', 'user', 'menu', 'dms_search']);

        // Labelling information for view
        $data['title'] = $title;
        $data['heading'] = $title;

        // nav_bar setup
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('', $this);

        // Home page menu sections
        $defs = $this->menu->get_section_menu_def($menu_config_db, $menu_section_table, $menu_item_table);
        $data['qs_section_defs'] = $defs;

        // Which sub view to load?
        $data['sub_view_name'] = $sub_view_name;
        $data['splash_view_name'] = ($splash_view_name)?$splash_view_name:'splash_default';
        $data['page_menu_root'] = $this->page_menu_root;

        echo view($this->page_menu_root . '/page_menu', $data);
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
        echo "Go <a href='".site_url("gen/cart/fly")."'>here</a> for page layout using 'flying' menu sections\n";
        echo "<br><br>";
        echo "Go <a href='".site_url("gen/cart/sections")."'>here</a> for page layout using static menu sections \n";
    }

    // --------------------------------------------------------------------
    function admin($layout = 'fly')
    {
        $selectedLayout = $layout == 'fly' ? 'sub_view_fly_menus' : 'sub_view_section_menus' ;
        $this->_page_menu("DMS Admin", $selectedLayout, 'splash_admin', "dms_admin_menu.db");
    }

    /**
     * Display the home page
     * https://dms2.pnl.gov/gen/welcome
     */
    function welcome()
    {
        $this->_page_menu("Welcome to DMS", "sub_view_welcome");
    }


    /**
     * Construct the side menu
     * https://dms2.pnl.gov/gen/side_menu
     */
    function side_menu()
    {
        helper(['menu', 'dms_search']);
        $this->menu = model('\\App\\Models\\Dms_menu');
        echo view('menu_panel');
    }

    /**
     * Return the side menu items as JSON
     * https://dms2.pnl.gov/gen/side_menu_objects
     */
    function side_menu_objects()
    {
        helper(['menu', 'dms_search']);
        $this->menu = model('\\App\\Models\\Dms_menu');

        $menu_def = $this->menu->get_menu_def("dms_menu.db", "menu_def");
        $items = build_side_menu_object_tree($menu_def, '');
        echo json_encode($items);
    }

    /**
     * Show session information
     * https://dms2.pnl.gov/gen/show_session
     */
    function show_session()
    {
        // echo DmsBase::var_dump_ex($_SESSION);

        echo "Session ID: ". session_id  () . "<hr />";
        echo "SID: ". SID . "<hr />";

        foreach($_SESSION as $k => $v) {
            echo $k . "<br />" . serialize($v) . "<hr />";
        }
    }

    /**
     * Show configuration info
     * https://dms2.pnl.gov/gen/info
     */
    function info()
    {
        // If the authentication type is basic, PHP_AUTH_PW will show the password as clear text
        // The following checks for this and obfuscates the password if needed
        $serverVars = $_SERVER;
        $savedPassword = "";

        if (array_key_exists ('PHP_AUTH_PW' , $serverVars )) {
            $savedPassword = $serverVars["PHP_AUTH_PW"];
            $_SERVER["PHP_AUTH_PW"] = "******** (masked by app/Controllers/Gen.php)";
        }

        echo phpinfo();

        DmsBase::var_dump_ex($_SERVER);

        if (strlen($savedPassword) > 0) {
            $_SERVER["PHP_AUTH_PW"] = $savedPassword;
        }

    }

    /**
     * Read the restricted actions defined in the master_authorization SQLite database
     * Display the results in an HTML table
     * https://dms2.pnl.gov/gen/auth
     */
    function auth()
    {
        // Load the authorization model
        $this->auth = model('\\App\\Models\\Dms_authorization');
        $rows = $this->auth->get_master_restriction_list();

        $this->table = new \CodeIgniter\View\Table();
        $tmpl = array ('table_open' => '<table border="1" cellpadding="4" cellspacing="0">');
        $this->table->setTemplate($tmpl);

        $this->table->setHeading('Page Family', 'Action', 'Restrictions');

        foreach($rows as $row) {
            array_shift($row); // get rid of id column
            $this->table->addRow($row);
        }

        echo $this->table->generate();
    }

    /**
     * Show statistics on datasets, experiments, etc.
     * https://dms2.pnl.gov/gen/stats
     */
    function stats()
    {
        $this->model = model('\\App\\Models\\Dms_statistics');
        helper(['form', 'user', 'dms_stats', 'dms_search', 'menu']);

        // nav_bar setup
        $this->menu = new \App\Models\Dms_menu();
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('Statistics', $this);

        // Labelling information for view
        $data['title'] = "DMS Statistics";
        $data['heading'] = $data['title'];

        $result = $this->model->get_stats();
        $data['results'] = $result;

        echo view('special/statistics', $data);

    }
}
?>
