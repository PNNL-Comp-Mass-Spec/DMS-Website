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

        session_start();

        $this->color_code = config('App')->version_color_code;
        $this->help_page_link = config('App')->pwiki;
        $this->help_page_link .= config('App')->wikiHelpLinkPrefix;
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
        $this->menu = model('App\Models\Dms_menu');

        $data['nav_bar_menu_items']= $this->get_basic_nav_bar_items();

        echo view('special/freezer_tree', $data);
    }

    // --------------------------------------------------------------------
    // AJAX
    // https://dms2.pnl.gov/freezer/get_freezers
    // https://dmsdev.pnl.gov/freezer/get_freezers
    function get_freezers()
    {
        $this->freezer = model('App\Models\Freezer_model');

        $frzrs = $this->freezer->get_freezers();
        $items = $this->freezer->build_freezer_location_list('Freezer', $frzrs, $this);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    // https://dms2.pnl.gov/freezer/get_locations
    // https://dmsdev.pnl.gov/freezer/get_locations
    function get_locations()
    {
        $this->freezer = model('App\Models\Freezer_model');

        $Type = $this->request->getPost('Type');
        $Freezer = $this->request->getPost('Freezer');
        $Shelf = $this->request->getPost('Shelf');
        $Rack = $this->request->getPost('Rack');
        $Row = $this->request->getPost('Row');
        // $Col = $this->request->getPost('Col');

        $sub_type = $this->freezer->get_sub_location_type($Type);
        $frzrs = $this->freezer->get_locations($sub_type, $Freezer, $Shelf, $Rack, $Row);
        $items = $this->freezer->build_freezer_location_list($sub_type, $frzrs, $this);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function get_containers()
    {
        $this->freezer = model('App\Models\Freezer_model');

        $location = $this->request->getPost('Location');

        $containers = $this->freezer->get_containers($location);
        $items = $this->freezer->build_container_list($containers);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function find_container()
    {
        $this->freezer = model('App\Models\Freezer_model');

        $container = $this->request->getPost('Container');

        $containers = $this->freezer->find_container($container);
        $items = $this->freezer->build_container_list($containers);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function find_location()
    {
        $this->freezer = model('App\Models\Freezer_model');

        $location = $this->request->getPost('Location');

        $locations = $this->freezer->find_location($location);
        $items = $this->freezer->build_freezer_location_list('', $locations, $this);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    // https://dms2.pnl.gov/freezer/find_available_location
    function find_available_location()
    {
        $this->freezer = model('App\Models\Freezer_model');

        $location = $this->request->getPost('Location');

        $locations = $this->freezer->find_available_location($location);
        $items = $this->freezer->build_freezer_location_list('', $locations, $this);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    // https://dms2.pnl.gov/freezer/find_newest_containers
    function find_newest_containers()
    {
        $this->freezer = model('App\Models\Freezer_model');

        //$location = $this->request->getPost('Location');

        $locations = $this->freezer->find_newest_containers();
        $items = $this->freezer->build_freezer_location_list('', $locations, $this);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/test
    function test()
    {
        $this->freezer = model('App\Models\Freezer_model');
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
    // https://dms2.pnl.gov/freezer/show/-20_Staging
    // https://dms2.pnl.gov/freezer/show/1208C
    //
    // https://dmsdev.pnl.gov/freezer/show/-20_Staging
    // https://dmsdev.pnl.gov/freezer/show/1208C
    function show($id)
    {
        helper(['freezer_helper', 'url', 'text', 'user', 'dms_search', 'menu']);
        $this->table = new \CodeIgniter\View\Table();
        $this->db = \Config\Database::connect();

        // labelling information for view
        $data['title'] = "Freezer Map";
        $data['heading'] = "Freezer Map";

        // navbar support
        $this->menu = model('App\Models\Dms_menu');
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('Detail_Reports', $this);

        // populate array of storage locations
        $sql = "";
        $sql .= "SELECT DISTINCT Location, Freezer, Shelf, Rack ";
        $sql .= "FROM V_Material_Location_List_Report ";
        $sql .= "WHERE Status = 'Active' ";
        $sql .= "ORDER BY Location, Freezer, Shelf, Rack";
        //
        $result = $this->db->query($sql);
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
        // show locations map in tables
        $fc_url = site_url("freezer/contents");
        $tmpl = array (
            'table_open'  => '<table border="1" cellpadding="2" cellspacing="1" class="EPag">',
            'heading_cell_start' => '<th class="block_header" colspan="7">'
        );
        $this->table->setTemplate($tmpl);
        //
        foreach($storage as $freezer => $f) {
            $c_url = "<a href='$fc_url/$freezer'>Contents</a>";
            $this->table->setHeading("Freezer:$freezer $c_url");
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
                $this->table->addRow($tr);
            }
            $data['content'] .= $this->table->generate() . '<br>';
            $this->table->clear();
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
        $this->table = new \CodeIgniter\View\Table();
        $this->db = \Config\Database::connect();

        // labelling information for view
        $data['title'] = "Freezer";
        $data['heading'] = "Freezer";

        // navbar support
        $this->menu = model('App\Models\Dms_menu');
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('List_Report', $this);

        // optional limits on what to include
        $uri = $this->request->uri;
        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();
        $freezer_spec = $uri->getSegment(3);
        $shelf_spec = $uri->getSegment(4);
        $rack_spec = $uri->getSegment(5);

        // populate array of storage locations
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
        $result = $this->db->query($sql);
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

        // populate array of location contents
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
        $result = $this->db->query($sql);
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
        $data['table'] = $this->table;

        echo view('special/freezer', $data);
    }

    // --------------------------------------------------------------------
    // https://dms2.pnl.gov/freezer/config
    // https://dmsdev.pnl.gov/freezer/config
    function config()
    {
        helper(['freezer_helper', 'url', 'text', 'user', 'dms_search', 'menu', 'form']);
        $this->table = new \CodeIgniter\View\Table();
        $this->db = \Config\Database::connect();

        $uri = $this->request->uri;
        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();
        $freezer_spec = $uri->getSegment(3);

        // labelling information for view
        $data['title'] = "Freezer Matrix";
        $data['heading'] = "Freezer $freezer_spec Matrix";

        // navbar support
        $this->menu = model('App\Models\Dms_menu');
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('List_Report', $this);

        // table styling
        $table_setup = "border='1' cellpadding='2' cellspacing='1' class='mytable'";
        $tstyl = " style='height:100%; width:100%; background-color:#abc; position:relative;'";

        // get list of rows and columns for given freezer
        $sql = "";
        $sql .= "SELECT Location_ID AS ID, Location, Shelf, Rack, Row, Col, Status ";
        $sql .= "FROM V_Material_Locations ";
        $sql .= "WHERE Freezer_Tag LIKE '%$freezer_spec%' ";
        $sql .= "AND NOT Row = 'na' AND NOT Col = 'na' ";
        $sql .= "ORDER BY Shelf, Rack, Row, Col ";
        $rc_result = $this->db->query($sql);
        if(!$rc_result) {
            $currentTimestamp = date("Y-m-d");
            echo "Error loading container row/column info; see writable/logs/log-$currentTimestamp.php";
            return;
        }
        $locs = $rc_result->getResultArray();

        // build nested array representation of freezer locations
        $fzr = make_freezer_matrix_array($locs);

        // make set of inner row-column tables
        $otr = make_matrix_row_col_tables($fzr, $table_setup, $tstyl);

        // render the final table
        $tbs = render_matrix_table($otr, $table_setup);

        // make freezer dropdown
        $js = "id='freezer_list' onchange='dmsjs.goToSelectedPage(\"freezer_list\");'";
        $data['picker'] = form_dropdown("freezer_list", $this->freezer_list(), null, $js);

        $data['tbs'] = $tbs;

        echo view('special/freezer_matrix', $data);
    }

    // --------------------------------------------------------------------
    private
    function freezer_list()
    {
        $this->freezer = model('App\Models\Freezer_model');

        $frzrs = $this->freezer->get_freezers();
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
