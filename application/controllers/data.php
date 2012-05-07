<?php
// --------------------------------------------------------------------
// Features related to utility_queries table (mostly developmental at this point)
// --------------------------------------------------------------------

class Data extends CI_Controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		parent::Controller();
	}

	// --------------------------------------------------------------------
	// ad hoc query stuff
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	// http://dmsdev.pnl.gov/data/ax/<output format>/<query name>/<config source>/<filter value>/.../<filter value>
	// Example: http://dmsdev.pnl.gov/data/ax/dump/list_report/instrument/vorbi
	// Example: http://dmsdev.pnl.gov/data/ax/tsv/aux_info_categories/aux_info_def/500
	// --------------------------------------------------------------------
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
	function check_access($action, $output_message = TRUE)
	{
		return TRUE;
	}

	// --------------------------------------------------------------------
	function set_up_nav_bar($page_type)
	{
		$this->help_page_link = $this->config->item('pwiki') . $this->config->item('wikiHelpLinkPrefix');
		$this->load->helper(array('menu', 'dms_search'));
		$this->load->model('dms_menu', 'menu', TRUE);
		return get_nav_bar_menu_items($page_type);
	}
	
	// --------------------------------------------------------------------
	// http://dmsdev.pnl.gov/data/lz/<output format>/<config source>/<query name>
	// http://dmsdev.pnl.gov/data/lz/tsv/ad_hoc_query/campaign
	// http://dmsdev.pnl.gov/data/lz/tsv/ad_hoc_query/lcms_requested_run
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
		
		switch($output_format) {
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
			case 'xml_dataset':
				$rows = $query->result_array();
				$this->cu->load_lib('general_query', '', '');
				$this->general_query->xml_dataset($rows);
				break;
		}	
	}

	// --------------------------------------------------------------------
	// http://dmsdev.pnl.gov/data/lr/grk/user/report
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
			case 'report_sql':
				$this->cu->load_lib('list_report_ah', $config_name, $config_source);
				$this->list_report_ah->report_sql();
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

	// --------------------------------------------------------------------
	// get list of URLs for ad hoc list reports
	function lr_menu($config_source = "ad_hoc_query", $config_name = 'utility_queries')
	{
		$configDBFolder = "application/model_config/";
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
		$this->load->vars($data);	
		$this->load->view('basic');
	}
	
}
?>