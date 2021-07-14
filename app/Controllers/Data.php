<?php
namespace App\Controllers;

/**
 * Features related to utility_queries table (mostly developmental at this point)
 */
class Data extends BaseController {

    // --------------------------------------------------------------------
    // ad hoc query stuff
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
        session_start();
        $this->load->helper(array('url'));
        $this->load->library('controller_utility', '', 'cu');
        $this->cu->load_lib('general_query', '', ''); // $config_name, $config_source

        $input_parms = $this->general_query->get_query_values_from_url();
        $this->general_query->setup_query($input_parms);
        $this->general_query->output_result($input_parms->output_format);
    }

    // --------------------------------------------------------------------
    // list report stuff
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    function check_access($action, $output_message = true)
    {
        return true;
    }

    // --------------------------------------------------------------------
    function set_up_nav_bar($page_type)
    {
        $this->help_page_link = $this->config->item('pwiki') . $this->config->item('wikiHelpLinkPrefix');
        $this->load->helper(array('menu', 'dms_search'));
        $this->menu = model('App\Models\dms_menu');
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
        $this->load->library('controller_utility', '', 'cu');
        $this->load->helper(array('url', 'user'));
        $segs = array_slice($this->uri->segment_array(), 2);
//print_r($_POST); echo "\n";
        $output_format = $segs[0];
        $config_source = $segs[1];
        $config_name = $segs[2];

        // the list_report infrastructure needs this
        $this->my_tag = "data/lz/$output_format/$config_source/$config_name";
        $this->my_title = "";

        $this->cu->load_lib('list_report_ah', $config_name, $config_source);

        $this->list_report_ah->set_up_data_query();
        $query = $this->data_model->get_rows('filtered_and_sorted');

        $pageTitle = $config_source;

        switch(strtolower($output_format)) {
            case 'sql':
                echo $this->data_model->get_sql('filtered_and_sorted');
                break;
            case 'count':
                $rows = $query->result_array();
                echo "rows:".count($rows);
                break;
            case 'json':
                $rows = $query->result_array();
                echo json_encode($rows);
                break;
            case 'tsv':
                $rows = $query->result_array();
                $this->cu->load_lib('general_query', '', '');
                $this->general_query->tsv($rows);
                break;
            case 'html':
            case 'table':
                $rows = $query->result_array();
                $this->cu->load_lib('general_query', '', '');
                $this->general_query->html_table($rows, $pageTitle);
                break;
            case 'xml':
            case 'xml_dataset':
                $rows = $query->result_array();
                $this->cu->load_lib('general_query', '', '');
                $this->general_query->xml_dataset($rows, $pageTitle);
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
        session_start();
        $this->load->helper(array('url'));
        $this->load->library('controller_utility', '', 'cu');
        $this->cu->load_lib('general_query', '', ''); // $config_name, $config_source

        $input_parms = new stdClass ();
        $input_parms->output_format = ''; // $this->uri->segment(3);
        $input_parms->q_name = $this->uri->segment(4);
        $input_parms->config_source = $this->uri->segment(3);;
        $input_parms->filter_values = array_slice($this->uri->segment_array(), 4);

        $pfv = $this->input->post('filter_values');
        if($pfv) {
            $input_parms->filter_values = explode(',', $pfv);
        } else {
            $input_parms->filter_values = array_slice($this->uri->segment_array(), 4);
        }

        $this->general_query->setup_query($input_parms);

        $query = $this->model->get_rows('filtered_and_sorted'); // filtered_only  filtered_and_sorted
        echo json_encode($query->result());
//      echo $this->model->get_sql('filtered_and_sorted');
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
        $this->load->library('controller_utility', '', 'cu');
        $this->load->helper(array('url', 'user'));
        $segs = array_slice($this->uri->segment_array(), 2);

        $config_source = $segs[0];
        $config_name = $segs[1];
        $content_type = $segs[2];
        $option = (isset($segs[3]))?$segs[3]:'';

        // the list_report view needs this for setting up its various links
        $this->my_tag = "data/lr/$config_source/$config_name";
        $this->my_title = "";
        $this->my_config_db = $config_source;

        switch($content_type) {
            case 'report':
                $this->cu->load_lib('list_report_ah', $config_name, $config_source);
                $this->list_report_ah->list_report('report');
                break;
            case 'search':
                $this->cu->load_lib('list_report_ah', $config_name, $config_source);
                $this->list_report_ah->list_report('search');
                break;
            case 'report_filter':
                $this->cu->load_lib('list_report_ah', $config_name, $config_source);
                $this->list_report_ah->report_filter($option);
                break;
            case 'get_sql_comparison':
                $this->cu->load_lib('list_report_ah', $config_name, $config_source);
                $this->list_report_ah->get_sql_comparison($column_name);
                break;
            case 'report_data':
                $this->cu->load_lib('list_report_ah', $config_name, $config_source);
                $this->list_report_ah->report_data('rows');
                break;
            case 'reportinfol':
                $this->cu->load_lib('list_report_ah', $config_name, $config_source);
                $this->list_report_ah->report_info("sql");
                break;
            case 'report_paging':
                $this->cu->load_lib('list_report_ah', $config_name, $config_source);
                $this->list_report_ah->report_paging();
                break;
            case 'export':
                $this->cu->load_lib('list_report_ah', $config_name, $config_source);
                $this->list_report_ah->export($option);
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
        $CI =& get_instance();
        $configDBFolder = $CI->config->item('model_config_path');
        $dbFileName = $config_source . '.db';

        $dbFilePath = $configDBFolder.$dbFileName;
        $dbh = new PDO("sqlite:$dbFilePath");
        if(!$dbh) throw new Exception('Could not connect to menu config database at '.$dbFilePath);

        $this->load->helper(array('url'));
        $this->load->library('table');
        $this->table->set_template(array ('table_open'  => '<table class="EPag">'));
        $this->table->set_heading('Page', 'Table', 'DB');

        $links = array();
        foreach ($dbh->query("SELECT * FROM $config_name ORDER BY label", PDO::FETCH_OBJ) as $obj) {
            $links['link'] = anchor("data/lr/$config_source/$obj->name/report", $obj->label);
            $links['table'] = $obj->table;
            $links['db'] = $obj->db;
            $this->table->add_row($links);
        }
        $edit_link = "<div style='padding:5px;'>" . anchor("config_db/show_db/$dbFileName", 'Config db') . "</div>";

        $data['title'] = 'Custom List Reports';
        $data['content'] = $edit_link . $this->table->generate();
        echo view('basic', $data);
    }
}
?>
