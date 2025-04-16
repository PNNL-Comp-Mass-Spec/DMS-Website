<?php
namespace App\Controllers;

class Freezer extends DmsBase {
    function __construct()
    {
        $this->my_tag = "freezer";
        $this->my_title = "Freezer";
        $this->helpers = array_merge($this->helpers, ['url', 'text', 'user']);
    }

    /**
     * CodeIgniter 4 Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //Ensure a session is initialized
        $session = \Config\Services::session();

        $this->setupHelpPageLink();
    }

    // --------------------------------------------------------------------
    function index()
    {
        echo '<h2>Howdy</h2><p>You probably want <br><a href="https://dms2.pnl.gov/freezer/tree">https://dms2.pnl.gov/freezer/tree</a> or<br><a href="https://dms2.pnl.gov/freezers/report">https://dms2.pnl.gov/freezers/report</a></p>';
    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/tree
    // https://dmsdev.pnl.gov/freezer/tree
    function tree()
    {
        helper(['menu', 'dms_search']);

        $data['nav_bar_menu_items']= $this->get_basic_nav_bar_items();

        echo view('special/freezer_tree', $data);
    }

    // --------------------------------------------------------------------
    // AJAX
    // https://dms2.pnl.gov/freezer/get_freezers
    // https://dmsdev.pnl.gov/freezer/get_freezers
    function get_freezers()
    {
        $freezer = model('App\Models\Freezer_model');

        $frzrs = $freezer->get_freezers();
        $items = $freezer->build_freezer_location_list('Freezer', $frzrs, $this);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    // https://dms2.pnl.gov/freezer/get_locations
    // https://dmsdev.pnl.gov/freezer/get_locations
    function get_locations()
    {
        $freezer = model('App\Models\Freezer_model');

        $Type = $this->request->getPost('Type');
        $Freezer = $this->request->getPost('Freezer');
        $Shelf = $this->request->getPost('Shelf');
        $Rack = $this->request->getPost('Rack');
        $Row = $this->request->getPost('Row');
        // $Col = $this->request->getPost('Col');

        $sub_type = $freezer->get_sub_location_type($Type);
        $frzrs = $freezer->get_locations($sub_type, $Freezer, $Shelf, $Rack, $Row);
        $items = $freezer->build_freezer_location_list($sub_type, $frzrs, $this);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function get_containers()
    {
        $freezer = model('App\Models\Freezer_model');

        $location = $this->request->getPost('Location');

        $containers = $freezer->get_containers($location);
        $items = $freezer->build_container_list($containers);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function find_container()
    {
        $freezer = model('App\Models\Freezer_model');

        $container = $this->request->getPost('Container');

        $containers = $freezer->find_container($container);
        $items = $freezer->build_container_list($containers);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function find_location()
    {
        $freezer = model('App\Models\Freezer_model');

        $location = $this->request->getPost('Location');

        $locations = $freezer->find_location($location);
        $items = $freezer->build_freezer_location_list('', $locations, $this);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    // https://dms2.pnl.gov/freezer/find_available_location
    function find_available_location()
    {
        $freezer = model('App\Models\Freezer_model');

        $location = $this->request->getPost('Location');

        $locations = $freezer->find_available_location($location);
        $items = $freezer->build_freezer_location_list('', $locations, $this);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    // https://dms2.pnl.gov/freezer/find_newest_containers
    function find_newest_containers()
    {
        $freezer = model('App\Models\Freezer_model');

        //$location = $this->request->getPost('Location');

        $locations = $freezer->find_newest_containers();
        $items = $freezer->build_freezer_location_list('', $locations, $this);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/test
    function test()
    {
        $freezer = model('App\Models\Freezer_model');
        $testLocs = array(
            "80B.na.na.na.na",
            "80B.1.na.na.na",
            "80B.1.2.na.na",
            "80B.1.2.1.na",
            "80B.1.2.1.2",
        );
        foreach($testLocs as $location) {
            $locations = $freezer->find_location($location);
            echo $location . "=>" .$freezer->get_location_type($locations[0]) ."\n";
        }
    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/show/-20_Staging
    // https://dms2.pnl.gov/freezer/show/1208C
    //
    // https://dmsdev.pnl.gov/freezer/show/-20_Staging
    // https://dmsdev.pnl.gov/freezer/show/1208C
    function show($id)
    {
        helper(['freezer_helper', 'url', 'text', 'user', 'dms_search', 'menu']);
        $table = new \CodeIgniter\View\Table();
        $db = \Config\Database::connect();
        $this->updateSearchPath($db);

        // Labelling information for view
        $data['title'] = "Freezer Map";
        $data['heading'] = "Freezer Map";

        // navbar support
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('Detail_Reports', $this);

        // Populate array of storage locations
        $sql = "";
        $sql .= "SELECT DISTINCT Location, Freezer, Shelf, Rack ";
        $sql .= "FROM V_Material_Location_List_Report ";
        $sql .= "WHERE Status = 'Active' ";
        $sql .= "ORDER BY Location, Freezer, Shelf, Rack";
        //
        $result = $db->query($sql);
        //
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "Error loading active freezer locations; see writable/logs/log-$currentTimestamp.php";
            return;

        }
        //
        $storage = array();
        $rows = $result->getResultArray();
        foreach($rows as $r) {
            $storage[$r['Freezer']][$r['Shelf']][$r['Rack']] = '' ;
        }

        $data['content'] = '';

        // Show locations map in tables
        $fc_url = site_url("freezer/contents");
        $tmpl = array (
            'table_open'  => '<table border="1" cellpadding="2" cellspacing="1" class="EPag">',
            'heading_cell_start' => '<th class="block_header" colspan="7">'
        );
        $table->setTemplate($tmpl);
        //
        foreach($storage as $freezer => $f) {
            $c_url = "<a href='$fc_url/$freezer'>Contents</a>";
            $table->setHeading("Freezer:$freezer $c_url");
            foreach($f as $shelf => $s) {
                $tr = array();
                $s_url = "<a href='$fc_url/$freezer/$shelf'>Contents</a>";
                $tr[] = "<span style='font-weight:bold;'> ". "Shelf:".$shelf . " &nbsp; " . $s_url . "</span>";
                foreach($s as $rack => $rk) {
                    if($rack == 'na') {

                    } else {
                        $r_url = "<a href='$fc_url/$freezer/$shelf/$rack'>Contents</a>";
                        $tr[] = "Rack:".$rack . " &nbsp; " . $r_url;
                    }
                }
                $table->addRow($tr);
            }
            $data['content'] .= $table->generate() . '<br>';
            $table->clear();
        }
        echo view('basic', $data);

    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/contents/-20_Staging
    // https://dms2.pnl.gov/freezer/contents/1208B
    //
    // https://dmsdev.pnl.gov/freezer/contents/-20_Staging
    // https://dmsdev.pnl.gov/freezer/contents/1208B
    // https://dmsdev.pnl.gov/freezer/contents/2240C
    function contents()
    {
        helper(['freezer_helper', 'url', 'text', 'user', 'dms_search', 'menu']);
        $db = \Config\Database::connect();
        $this->updateSearchPath($db);

        // Labelling information for view
        $data['title'] = "Freezer";
        $data['heading'] = "Freezer";

        // navbar support
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('List_Report', $this);

        // Optional limits on what to include
        $uri = $this->request->getUri();

        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();
        $freezer_spec = $uri->getSegment(3);
        $shelf_spec = $uri->getSegment(4);
        $rack_spec = $uri->getSegment(5);

        // Populate array of storage locations
        $sql = "";
        $sql .= "SELECT Freezer, Shelf, Rack, Row, Col, Location, Available ";
        $sql .= "FROM V_Material_Location_List_Report ";
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
        $result = $db->query($sql);
        //
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "Error loading freezer locations; see writable/logs/log-$currentTimestamp.php";
            return;
        }
        //
        $storage = array();
        $rows = $result->getResultArray();
        foreach($rows as $r) {
            $storage[$r['Freezer']][$r['Shelf']][$r['Rack']][$r['Row']][$r['Col']] = array( 'Location' => $r['Location'], 'Available' => $r['Available']) ;
        }

        // Populate array of location contents
        $sql = "";
        $sql .= "SELECT Container, Location, Comment ";
        $sql .= "FROM V_Material_Container_Locations ";
        if($freezer_spec) {
            $sql .= "WHERE Freezer_Tag LIKE '%$freezer_spec%' ";
        }
        if($shelf_spec) {
            $sql .= "AND (Shelf = '$shelf_spec') ";
        }
        if($rack_spec) {
            $sql .= "AND (Rack = '$rack_spec') ";
        }
        //
        $result = $db->query($sql);
        //
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "Error loading containers; see writable/logs/log-$currentTimestamp.php";
            return;
        }
        //
        $contents = array();
        $rows = $result->getResultArray();
        foreach($rows as $r) {
            $contents[$r['Location']][] = array( 'Container' => $r['Container'], 'Comment' => $r['Comment']);
        }

        $data['storage'] = $storage;
        $data['contents'] = $contents;

        $data['check_access'] = [$this, 'check_access'];
        $data['table'] = new \CodeIgniter\View\Table(); // Create for use in the view

        echo view('special/freezer', $data);
    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/config
    // https://dmsdev.pnl.gov/freezer/config
    function config()
    {
        helper(['freezer_helper', 'url', 'text', 'user', 'dms_search', 'menu', 'form']);
        $db = \Config\Database::connect();
        $this->updateSearchPath($db);

        $uri = $this->request->getUri();
        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();
        $freezer_spec = $uri->getSegment(3);

        // Labelling information for view
        $data['title'] = "Freezer Matrix";
        $data['heading'] = "Freezer $freezer_spec Matrix";

        // navbar support
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('List_Report', $this);

        // Table styling
        $table_setup = "border='1' cellpadding='2' cellspacing='1' class='mytable'";
        $tstyl = " style='height:100%; width:100%; background-color:#abc; position:relative;'";

        // Get list of rows and columns for given freezer
        $sql = "";
        $sql .= "SELECT Location_ID AS ID, Location, Shelf, Rack, Row, Col, Status ";
        $sql .= "FROM V_Material_Locations ";
        $sql .= "WHERE Freezer_Tag LIKE '%$freezer_spec%' ";
        $sql .= "AND NOT Row = 'na' AND NOT Col = 'na' ";
        $sql .= "ORDER BY Shelf, Rack, Row, Col ";
        $rc_result = $db->query($sql);
        if(!$rc_result) {
            $currentTimestamp = date("Y-m-d");
            echo "Error loading container row/column info; see writable/logs/log-$currentTimestamp.php";
            return;
        }
        $locs = $rc_result->getResultArray();

        // Build nested array representation of freezer locations
        $fzr = make_freezer_matrix_array($locs);

        // Make set of inner row-column tables
        $otr = make_matrix_row_col_tables($fzr, $table_setup, $tstyl);

        // Render the final table
        $tbs = render_matrix_table($otr, $table_setup);

        // Make freezer dropdown
        $js = "id='freezer_list' onchange='dmsjs.goToSelectedPage(\"freezer_list\");'";
        $data['picker'] = form_dropdown("freezer_list", $this->freezer_list(), null, $js);

        $data['tbs'] = $tbs;

        echo view('special/freezer_matrix', $data);
    }

    // --------------------------------------------------------------------
    private
    function freezer_list()
    {
        $freezer = model('App\Models\Freezer_model');

        $frzrs = $freezer->get_freezers();
        $lst = array();
        foreach($frzrs as $frzr) {
            $r = $frzr["freezer"];
            $r = preg_split ('/ /', $r);
            $f = (count($r) > 1)?$r[1]:$r[0];
            $l = site_url("freezer/config/$f");
            $lst[$l] = $f;
        }
        return $lst;
    }
}
?>
