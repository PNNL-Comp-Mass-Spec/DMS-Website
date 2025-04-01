<?php
namespace App\Controllers;

use CodeIgniter\Database\SQLite3\Connection;

/**
 * Features related to utility_queries table (mostly developmental at this point)
 */
class Data extends BaseController {

    public $my_config_db = null;

    // --------------------------------------------------------------------
    // Ad hoc query stuff
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    /**
     * Export results in various formats
     * Expected URL format:
     *  http://dms2.pnl.gov/data/ax/<output format>/<query name>/<config source>/<filter value>/.../<filter value>
     *
     * Example URLs:
     *  https://dms2.pnl.gov/data/ax/json/list_report/instrument/vorbietd02
     *  https://dms2.pnl.gov/data/ax/table/list_report/instrument/vorbietd02
     *  https://dms2.pnl.gov/data/ax/tsv/list_report/instrument/vorbietd02
     *  https://dms2.pnl.gov/data/ax/xml/list_report/instrument/vorbietd02
     *  https://dms2.pnl.gov/data/ax/sql/list_report/instrument/vorbietd02
     *  https://dms2.pnl.gov/data/ax/dump/list_report/instrument/vorbietd02
     *
     *  https://dms2.pnl.gov/data/ax/json/detail_report/user/D3l243
     *  https://dms2.pnl.gov/data/ax/table/detail_report/user/D3l243
     *  https://dms2.pnl.gov/data/ax/tsv/detail_report/user/D3l243
     *  https://dms2.pnl.gov/data/ax/xml/detail_report/user/D3l243
     *  https://dms2.pnl.gov/data/ax/sql/detail_report/user/D3l243
     *  https://dms2.pnl.gov/data/ax/dump/detail_report/user/D3l243
     *
     *  https://dms2.pnl.gov/data/ax/tsv/aux_info_categories/aux_info_def/500
     *  https://dms2.pnl.gov/data/ax/table/aux_info_categories/aux_info_def/500
     */
    function ax()
    {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        helper(['url']);
        $general_query = $this->getLibrary('General_query', '', ''); // $config_name, $config_source

        $input_parms = $general_query->get_query_values_from_url();
        $general_query->setup_query($input_parms);
        $general_query->output_result($input_parms->output_format);
    }

    // --------------------------------------------------------------------
    // List report stuff
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    function check_access($action, $output_message = true)
    {
        return true;
    }

    // --------------------------------------------------------------------
    function set_up_nav_bar($page_type)
    {
        $this->setupHelpPageLink();
        helper(['menu', 'dms_search']);
        return get_nav_bar_menu_items($page_type, $this);
    }

    /**
     * Export results in various formats
     * Expected URL format:
     *  https://dms2.pnl.gov/data/lz/<output format>/<config source>/<query name>
     *
     * Example URLs:
     *  https://dms2.pnl.gov/data/lz/table/ad_hoc_query/job_operations
     *  https://dms2.pnl.gov/data/lz/tsv/ad_hoc_query/job_operations
     *  https://dms2.pnl.gov/data/lz/tsv/ad_hoc_query/lcms_requested_run
     *  https://dms2.pnl.gov/data/lz/sql/ad_hoc_query/campaign
     *  https://dms2.pnl.gov/data/lz/count/ad_hoc_query/campaign
     *  https://dms2.pnl.gov/data/lz/json/ad_hoc_query/capture_operations
     *  https://dms2.pnl.gov/data/lz/xml/ad_hoc_query/dms_emsl_inst
     *  https://dms2.pnl.gov/data/lz/tsv/user/list_report/
     *  https://dms2.pnl.gov/data/lz/xml/user/list_report/
     *  https://dms2.pnl.gov/data/lz/tsv/user/detail_report/
     */
    function lz()
    {
        helper(['url', 'user']);
        $segs = decodeSegments(array_slice($this->request->uri->getSegments(), 2));
//print_r($_POST); echo "\n";
        $output_format = $segs[0];
        $config_source = $segs[1];
        $config_name = $segs[2];

        // The list_report infrastructure needs this
        $this->my_tag = "data/lz/$output_format/$config_source/$config_name";
        $this->my_title = "";

        $list_report_ah = $this->getLibrary('List_report_ah', $config_name, $config_source);
        $this->loadDataModel($config_name, $config_source);

        $list_report_ah->set_up_data_query();
        $query = $this->data_model->get_rows('filtered_and_sorted');

        $pageTitle = $config_source;

        switch(strtolower($output_format)) {
            case 'sql':
                echo $this->data_model->get_sql('filtered_and_sorted');
                break;
            case 'count':
                $rows = $query->getResultArray();
                echo "rows:".count($rows);
                break;
            case 'json':
                $rows = $query->getResultArray();
                echo json_encode($rows);
                break;
            case 'tsv':
                $rows = $query->getResultArray();
                $general_query = $this->getLibrary('General_query', '', '');
                $general_query->tsv($rows);
                break;
            case 'html':
            case 'table':
                $rows = $query->getResultArray();
                $general_query = $this->getLibrary('General_query', '', '');
                $general_query->html_table($rows, $pageTitle);
                break;
            case 'xml':
            case 'xml_dataset':
                $rows = $query->getResultArray();
                $general_query = $this->getLibrary('General_query', '', '');
                $general_query->xml_dataset($rows, $pageTitle);
                break;
        }
    }

    /**
     * Export results in JSON
     * Expected URL format:
     *  http://dmsdev.pnl.gov/data/json/<config source>/<query name>/<filter value>/.../<filter value>
     * Example URLs:
     *  http://dmsdev.pnl.gov/data/json/ad_hoc_query/osm_package_requests/101
     *  http://dmsdev.pnl.gov/data/json/ad_hoc_query/osm_package_datasets/101
     */
    function json()
    {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        helper(['url']);
        $uri = $this->request->uri;

        $general_query = $this->getLibrary('General_query', '', ''); // $config_name, $config_source

        $input_parms = new \App\Libraries\General_query_def ();
        // Don't trigger an exception if the segment index is too large
        $uri->setSilent();
        $input_parms->output_format = ''; // $uri->getSegment(3);
        $input_parms->q_name = decode_special_values($uri->getSegment(4));
        $input_parms->config_source = decode_special_values($uri->getSegment(3));

        $pfv = $this->request->getPost('filter_values');
        if($pfv) {
            $input_parms->filter_values = explode(',', $pfv);
        } else {
            $input_parms->filter_values = decodeSegments(array_slice($uri->getSegments(), 4));
        }

        $general_query->setup_query($input_parms);

        $this->loadDataModel($input_parms->q_name, $input_parms->config_source);
        $query = $this->data_model->get_rows('filtered_and_sorted'); // filtered_only or filtered_and_sorted
        echo json_encode($query->getResult());
//      echo $this->data_model->get_sql('filtered_and_sorted');
    }


    /**
     * Show data for ad-hoc queries
     * Example URLs:
     * https://dms2.pnl.gov/data/lr/ad_hoc_query/helper_inst_group_dstype/report
     * https://dms2.pnl.gov/data/lr/ad_hoc_query/capture_operations/report
     * https://dmsdev.pnl.gov/data/lr/grk/user/report
     */
    function lr()
    {
        helper(['url', 'user']);
        $segs = decodeSegments(array_slice($this->request->uri->getSegments(), 2));

        $config_source = $segs[0];
        $config_name = $segs[1];
        $content_type = $segs[2];
        $option = (isset($segs[3]))?$segs[3]:'';
        $column_name = (isset($segs[4]))?$segs[4]:'';

        // The list_report view needs this for setting up its various links
        $this->my_tag = "data/lr/$config_source/$config_name";
        $this->my_title = "";
        $this->my_config_db = $config_source;

        switch($content_type) {
            case 'report':
                $list_report_ah = $this->getLibrary('List_report_ah', $config_name, $config_source);
                $list_report_ah->list_report('report');
                break;
            case 'search':
                $list_report_ah = $this->getLibrary('List_report_ah', $config_name, $config_source);
                $list_report_ah->list_report('search');
                break;
            case 'report_filter':
                $list_report_ah = $this->getLibrary('List_report_ah', $config_name, $config_source);
                $list_report_ah->report_filter($option);
                break;
            case 'get_sql_comparison':
                $list_report_ah = $this->getLibrary('List_report', $config_name, $config_source);
                $list_report_ah->get_sql_comparison($column_name);
                break;
            case 'report_data':
                $list_report_ah = $this->getLibrary('List_report_ah', $config_name, $config_source);
                $list_report_ah->report_data('rows');
                break;
            case 'reportinfol':
                $list_report_ah = $this->getLibrary('List_report_ah', $config_name, $config_source);
                $list_report_ah->report_info("sql");
                break;
            case 'report_paging':
                $list_report_ah = $this->getLibrary('List_report_ah', $config_name, $config_source);
                $list_report_ah->report_paging();
                break;
            case 'export':
                $list_report_ah = $this->getLibrary('List_report_ah', $config_name, $config_source);
                $list_report_ah->export($option);
                break;
        }
    }

    /**
     * Get list of URLs for ad hoc list reports
     * @param type $config_source
     * @param type $config_name
     * @throws Exception
     */
    function lr_menu($config_source = "ad_hoc_query", $config_name = 'utility_queries')
    {
        $dbFileName = $config_source . '.db';

        helper(['config_db']);
        $dbFilePath = get_model_config_db_path($dbFileName)->path;
        $db = new Connection(['database' => $dbFilePath, 'dbdriver' => 'sqlite3']);

        helper(['url']);
        $table = new \CodeIgniter\View\Table();
        $table->setTemplate(array ('table_open'  => '<table class="EPag">'));
        $table->setHeading('Page', 'Table', 'DB');

        $links = array();
        foreach ($db->query("SELECT * FROM $config_name ORDER BY label")->getResultObject() as $obj) {
            $links['link'] = anchor("data/lr/$config_source/$obj->name/report", $obj->label);
            $links['table'] = $obj->table;
            $links['db'] = $obj->db;
            $table->addRow($links);
        }

        $db->close();
        $edit_link = "<div style='padding:5px;'>" . anchor("config_db/show_db/$dbFileName", 'Config db') . "</div>";

        $data['title'] = 'Custom List Reports';
        $data['content'] = $edit_link . $table->generate();
        echo view('basic', $data);
    }
}
?>
