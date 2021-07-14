<?php
namespace App\Controllers;

class Freezer extends Base_controller {

    var $my_tag = "freezer";

    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        session_start();
        helper(['url', 'string', 'user']);

        $this->color_code = $this->config->item('version_color_code');
        $this->help_page_link = $this->config->item('pwiki');
        $this->help_page_link .= $this->config->item('wikiHelpLinkPrefix');
    }

    // --------------------------------------------------------------------
    function index()
    {
        echo '<h2>Howdy</h2><p>You probably want <a href="http://dms2.pnl.gov/freezer/tree">http://dms2.pnl.gov/freezer/tree</a></p>';
    }

    // --------------------------------------------------------------------
    function tree()
    {
        helper(['menu', 'dms_search']);
        $this->menu = model('App\Models\dms_menu');

        $data['nav_bar_menu_items']= $this->get_basic_nav_bar_items();

        echo view('special/freezer_tree', $data);
    }

    // --------------------------------------------------------------------
    // AJAX
    function get_freezers()
    {
        $this->freezer = model('App\Models\freezer_model');

        $frzrs = $this->freezer->get_freezers();
        $items = $this->freezer->build_freezer_location_list('Freezer', $frzrs);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function get_locations()
    {
        $this->freezer = model('App\Models\freezer_model');

        $Type = $this->input->get_post('Type');
        $Freezer = $this->input->get_post('Freezer');
        $Shelf = $this->input->get_post('Shelf');
        $Rack = $this->input->get_post('Rack');
        $Row = $this->input->get_post('Row');
        // $Col = $this->input->get_post('Col');

        $sub_type = $this->freezer->get_sub_location_type($Type);
        $frzrs = $this->freezer->get_locations($sub_type, $Freezer, $Shelf, $Rack, $Row);
        $items = $this->freezer->build_freezer_location_list($sub_type, $frzrs);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function get_containers()
    {
        $this->freezer = model('App\Models\freezer_model');

        $location = $this->input->get_post('Location');

        $containers = $this->freezer->get_containers($location);
        $items = $this->freezer->build_container_list($containers);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function find_container()
    {
        $this->freezer = model('App\Models\freezer_model');

        $container = $this->input->get_post('Container');

        $containers = $this->freezer->find_container($container);
        $items = $this->freezer->build_container_list($containers);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function find_location()
    {
        $this->freezer = model('App\Models\freezer_model');

        $location = $this->input->get_post('Location');

        $locations = $this->freezer->find_location($location);
        $items = $this->freezer->build_freezer_location_list('', $locations);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function find_available_location()
    {
        $this->freezer = model('App\Models\freezer_model');

        $location = $this->input->get_post('Location');

        $locations = $this->freezer->find_available_location($location);
        $items = $this->freezer->build_freezer_location_list('', $locations);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    // AJAX
    function find_newest_containers()
    {
        $this->freezer = model('App\Models\freezer_model');

        //$location = $this->input->get_post('Location');

        $locations = $this->freezer->find_newest_containers();
        $items = $this->freezer->build_freezer_location_list('', $locations);
        echo json_encode($items);
    }

    // --------------------------------------------------------------------
    function test()
    {
        $this->freezer = model('App\Models\freezer_model');
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
    function show($id)
    {
        helper(['freezer_helper', 'url', 'string', 'user', 'dms_search', 'menu']);
        $this->load->library('table');
        $this->load->database();

        // labelling information for view
        $data['title'] = "Freezer Map";
        $data['heading'] = "Freezer Map";

        // navbar support
        $this->menu = model('App\Models\dms_menu');
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('Detail_Reports', $this);

        // populate array of storage locations
        $sql = "";
        $sql .= "SELECT DISTINCT Location, Freezer, CASE WHEN CHARINDEX('20Met.D', Location) = 0 THEN Shelf ELSE 'D-' + Shelf END AS Shelf, Rack ";
        $sql .= "FROM V_Material_Locations_List_Report ";
        $sql .= "WHERE Status = 'Active' ";
        $sql .= "ORDER BY Location, Freezer, Shelf, Rack";
        //
        $result = $this->db->query($sql);
        //
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "Error loading active freezer locations; see application/logs/log-$currentTimestamp.php";
            return;

        }
        //
        $storage = array();
        $rows = $result->result_array();
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
        $this->table->set_template($tmpl);
        //
        foreach($storage as $freezer => $f) {
            $c_url = "<a href='$fc_url/$freezer'>Contents</a>";
            $this->table->set_heading("Freezer:$freezer $c_url");
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
                $this->table->add_row($tr);
            }
            $data['content'] .= $this->table->generate() . '<br>';
            $this->table->clear();
        }
        echo view('basic', $data);

    }

    // --------------------------------------------------------------------
    function contents()
    {
        helper(['freezer_helper', 'url', 'string', 'user', 'dms_search', 'menu']);
        $this->load->library('table');
        $this->load->database();

        // labelling information for view
        $data['title'] = "Freezer";
        $data['heading'] = "Freezer";

        // navbar support
        $this->menu = model('App\Models\dms_menu');
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('List_Report', $this);

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
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "Error loading freezer locations; see application/logs/log-$currentTimestamp.php";
            return;
        }
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
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "Error loading containers; see application/logs/log-$currentTimestamp.php";
            return;
        }
        //
        $contents = array();
        $rows = $result->result_array();
        foreach($rows as $r) {
            $contents[$r['Location']][] = array( 'Container' => $r['Container'], 'Comment' => $r['Comment']);
        }

        $data['storage'] = $storage;
        $data['contents'] = $contents;

        echo view('special/freezer', $data);
    }

    // --------------------------------------------------------------------
    function config()
    {
        helper(['freezer_helper', 'url', 'string', 'user', 'dms_search', 'menu', 'form']);
        $this->load->library('table');
        $this->load->database();

        $freezer_spec = $this->uri->segment(3);

        // labelling information for view
        $data['title'] = "Freezer Matrix";
        $data['heading'] = "Freezer $freezer_spec Matrix";

        // navbar support
        $this->menu = model('App\Models\dms_menu');
        $data['nav_bar_menu_items']= get_nav_bar_menu_items('List_Report', $this);

        // table styling
        $table_setup = "border='1' cellpadding='2' cellspacing='1' class='mytable'";
        $tstyl = " style='height:100%; width:100%; background-color:#abc; position:relative;'";

        // get list of rows and columns for given freezer
        $sql = "";
        $sql .= "SELECT  [ID] ,[Tag] ,[Shelf] ,[Rack] ,[Row] ,[Col] ,[Status] ";
        $sql .= "FROM [T_Material_Locations] ";
        $sql .= "WHERE Freezer LIKE '%$freezer_spec%' ";
        $sql .= "AND NOT [Row] = 'na' AND NOT [Col] = 'na' ";
        $sql .= "ORDER BY Shelf, Rack, [Row], Col ";
        $rc_result = $this->db->query($sql);
        if(!$rc_result) {
            $currentTimestamp = date("Y-m-d");
            echo "Error loading container row/column info; see application/logs/log-$currentTimestamp.php";
            return;
        }
        $locs = $rc_result->result_array();

        // build nested array representation of freezer locations
        $fzr = make_freezer_matrix_array($locs);

        // make set of inner row-column tables
        $otr = make_matrix_row_col_tables($fzr, $table_setup, $tstyl);

        // render the final table
        $tbs = render_matrix_table($otr, $table_setup);

        // make freezer dropdown
        $js = "id='freezer_list' onchange='gamma.goToSelectedPage(\"freezer_list\");'";
        $data['picker'] = form_dropdown("freezer_list", $this->freezer_list(), null, $js);

        $data['tbs'] = $tbs;
        echo view('special/freezer_matrix', $data);
    }

    // --------------------------------------------------------------------
    private
    function freezer_list()
    {
        $this->freezer = model('App\Models\freezer_model');

        $frzrs = $this->freezer->get_freezers();
        $lst = array();
        foreach($frzrs as $frzr) {
            $r = $frzr["Freezer"];
            $r = preg_split ('/ /', $r);
            $f = (count($r) > 1)?$r[1]:$r[0];
            $l = site_url("freezer/config/$f");
            $lst[$l] = $f;
        }
        return $lst;
    }
}
?>
