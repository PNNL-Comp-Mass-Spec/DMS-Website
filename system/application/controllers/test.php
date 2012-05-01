<?php

class Test extends Controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->load->helper(array('url'));
		
		//session_start();
	}
	// --------------------------------------------------------------------
	function index()
	{
		echo "Controller for testing generation 3 components<br>";
		echo BASEPATH . ' - The full server path to the "system" folder<br>';
		echo APPPATH . '- The full server path to the "application" folder<br>';
	}

	// --------------------------------------------------------------------
	function column_filter($config_name, $config_source)
	{
		session_start();

		echo "Test column filter<br>";
		echo 'config_name: ' . $config_name . '<br>';
		echo 'config_source: ' . $config_source . '<br>';
		echo '<hr>';
	
		echo 'POST:<br>';
		print_r($_POST);
		echo '<hr>';

		$this->load->library('column_filter');
		$this->column_filter->init($config_name, $config_source);
		$col_filter = $this->column_filter->get_current_filter_values();

		$this->load->model('q_model', 'data_model');
		$this->data_model->init($config_name, $config_source);
		$cols = $this->data_model->get_col_names();

		echo 'cols:<br>';
		print_r($cols);
		echo '<hr>';		
		
		echo 'col_filter:<br>';
		print_r($col_filter);
		echo '<hr>';

		echo 'Cached: (' . $this->column_filter->get_storage_name() . ')<br>';
		print_r($this->column_filter->get_cached_value());
		echo '<hr>';

		$this->load->helper(array('form', 'filter'));
	
		echo form_open("test/column_filter/$config_name/$config_source");
		echo make_column_filter($cols, $col_filter);
		echo form_submit('mysubmit', 'Submit Post!');
		echo form_close();		
	}

	// --------------------------------------------------------------------
	function display_col_selection($config_name, $config_source)
	{
		session_start();

		echo "Test column selection<br>";
		echo 'config_name: ' . $config_name . '<br>';
		echo 'config_source: ' . $config_source . '<br>';
		echo '<hr>';

		echo 'POST:<br>';
		print_r($_POST);
		echo '<hr>';

		// it all starts with a model
		$this->load->model('q_model', 'model');
		$this->model->init($config_name, $config_source);
		$col_info = $this->model->get_column_info();
		
		$options = array();
		foreach($col_info as $obj) {
			$options[$obj->name] = $obj->name;
		}
		
		$selected_items = array();
		if(array_key_exists('foo', $_POST)) {
			$selected_items = $_POST['foo'];
		}
/*		
		echo 'Column info:<br>';
		print_r($col_info);
		echo '<hr>';		
*/
		$this->load->helper(array('form', 'html'));

		$checked_boxes = array();
		if(array_key_exists('bar', $_POST)) {
			$checked_boxes = $_POST['bar'];
		}
		
		$checkboxes = array();
		foreach($col_info as $obj) {
			$checkboxes[] = form_checkbox('bar[]', $obj->name, in_array($obj->name, $checked_boxes)) . $obj->name;
		}
		
//		$this->load->helper('test');
		echo form_open("test/display_col_selection/$config_name/$config_source");
		echo "<h3>This is what a column selector would be as a multiple selection list</h3>(contol-click to make multiple selections)<br>";
		echo form_multiselect('foo[]', $options, $selected_items, 'size="6"');
		echo "<h3>This is what a column selector would be as a discrete list of checkboxes</h3>";
		echo ul($checkboxes);
		echo "<br>";
		echo form_submit('mysubmit', 'Submit Post!');
		echo form_close();
	}

	// --------------------------------------------------------------------
	// stored procedure model (s_model) component tests
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	function s_model($test_num, $mode)
	{
		session_start();
		$this->load->model('s_model', 'model');
		$this->load->helper('test');

		switch($test_num) {
			case 'a':
				$ok = $this->model->init('GetParamFileCrosstab', 'get_paramfile_crosstab');
				if(!$ok) {
					echo  $this->model->get_error_text();
					break;
				}
				$obj = new stdClass();
				$obj->ParameterFileTypeName = 'Sequest';
				$obj->ParameterFileFilter = 'ETD';
		//		$obj->previewSql = 'bob';
		//		$obj->UseModMassAlternativeName = 'bob';
		//		$obj->ShowValidOnly = 'bob';
				$obj->ShowModSymbol = '1';
		//		$obj->ShowModName = 'bob';
		//		$obj->ShowModMass = 'bob';
		//		$obj->MassModFilterTextColumn = 'bob';
		//		$obj->MassModFilterText = 'bob';

				$ok = $this->model->execute_sproc($obj);
				if(!$ok) {
					echo  $this->model->get_error_text();
					break;
				}
				dump_s_model($this->model);
				break;
			case 'b': // 
				$ok = $this->model->init('operations_sproc', 'bogus'); // operations_sproc DoBogusOperation
				if(!$ok) {
					echo  $this->model->get_error_text();
					break;
				}
				$obj = new stdClass();
				$obj->mode = $mode; //'rowset'; // 'dump'
				$ok = $this->model->execute_sproc($obj);
				if(!$ok) {
					echo  $this->model->get_error_text();
					break;
				}
				dump_s_model($this->model);
				break;
		}
	}

	// --------------------------------------------------------------------
	// sql query model (q_model) component tests
	// --------------------------------------------------------------------

	// --------------------------------------------------------------------
	function predicate()
	{
		$this->load->model('q_model', 'model');
		$this->model->init('users');
		$this->model->set_table('TX');
		$this->model->add_predicate_item('AND', '~qc_shew', 'ContainsText', '~qc_shew');
		$this->model->add_predicate_item('AND', '`qc_shew', 'ContainsText', '`qc_shew');
		$this->model->add_predicate_item('AND', '`qc_%hew', 'ContainsText', '`qc_%hew');
		$this->model->add_predicate_item('AND', 'qc_shew*', 'ContainsText', 'qc_shew*');
		$this->model->add_predicate_item('AND', 'qc_shew', 'ContainsText', 'qc_shew');
		$this->model->add_predicate_item('AND', 'qc*sh?w', 'ContainsText', 'qc*sh?w');
		
//		echo $this->model->get_sql('filtered_and_sorted');
//		echo '<hr>';
		$this->model->convert_wildcards();
		echo $this->model->get_sql('filtered_and_sorted');
		echo '<hr>';
	}	

	// --------------------------------------------------------------------
	// http://dmsdev.pnl.gov/test/q_model/a 
	// create various kinds of q_model objects for different query types
	// and config databases.  make selective by adding predicate items
	// and show results
	function q_model($test_num)
	{
		$this->load->model('q_model', 'model');
		$this->load->library('secondary_filter');
		$this->load->helper('test');

		session_start();
		
		switch($test_num) {
			case 'a':
				$this->model->init('users');
				$this->model->add_predicate_item('AND', 'U_PRN', 'ContainsText', 'J');
				$this->model->add_predicate_item('AND', 'U_Name', 'ContainsText', 'K');
				$this->model->add_sorting_item('U_Name', '');
				$this->model->add_sorting_item('U_PRN', 'DESC');
				dump_q_model($this->model);
				break;
			case 'b':
				$this->model->init('dsj');
				$this->model->add_predicate_item('ARG', 'id', '-', '150');
				$this->model->add_predicate_item('ARG', 'tool', '-', 'Sequest');
				$this->model->add_predicate_item('ARG', 'mode', '-', 'NoDMSJobs');
				dump_q_model($this->model);
				break;
			case 'c':
				$this->model->init('list_report', 'instrument');
				$this->model->add_predicate_item('AND', 'Name', 'ContainsText', 'Orbi');
				$this->model->add_sorting_item('Name', 'ASC');
				dump_q_model($this->model, "filtered_and_sorted");
				break;
			case 'd':
				$this->model->init('detail_report', 'instrument');
				$this->model->add_predicate_item('AND', 'ID', 'MatchesText', '60');
				dump_q_model($this->model);
				break;
			case 'e':
				$this->model->init('list_report', 'pipeline_step_tools');
				$this->model->add_predicate_item('AND', 'Name', 'ContainsText', 'DTA');
				dump_q_model($this->model);
				break;
			case 'f':
				$this->model->init('entry_page', 'instrument');
				$this->model->add_predicate_item('AND', 'ID', 'MatchesText', '60');
				dump_q_model($this->model);
				break;
			case 'g':
				$this->model->init('list_report', 'dataset');
				$this->model->add_predicate_item('AND', 'Name', 'ContainsText', 'Rifle_2*05');
				dump_q_model($this->model);
				break;
		}
	}

	// --------------------------------------------------------------------
	// filter component tests
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	// http://dmsdev.pnl.gov/test/primary_filter
	function primary_filter($config_name, $config_source)
	{
		session_start();

//		$config_name = 'list_report';
//		$config_source = 'instrument';

		echo "Test of primary filter <br>";
		echo 'config_name: ' . $config_name . '<br>';
		echo 'config_source: ' . $config_source . '<br>';
		echo '<hr>';

		echo 'POST:<br>';
		print_r($_POST);
		echo '<hr>';
		
		// it all starts with a model
		$this->load->model('q_model', 'model');
		$this->model->init($config_name, $config_source);

		// create primary filter object and initialize with with data from cache/POST
		$this->load->library('primary_filter');
		$pfs = $this->model->get_primary_filter_specs();
		$this->primary_filter->init($config_name, $config_source, $pfs);
		$current_primary_filter_values = $this->primary_filter->get_cur_filter_values();

		echo "primary filter specs from model: <br>";
		print_r($pfs); 
		echo '<hr>';

		echo "current filter values from primary filter: <br>";
		print_r($current_primary_filter_values); 
		echo '<hr>';

		echo 'Cached: (' . $this->primary_filter->get_storage_name() . ')<br>';
		print_r($this->primary_filter->get_cached_value());
		echo '<hr>';

		// apply filter items to model's predicate
		echo 'Predicate:<br>';
		foreach(array_values($current_primary_filter_values) as $pi) {
			// FUTURE: make sure that all items that are arguments have non-blank values
			echo 'rel:"'. $pi['rel'] . '", col:"' . $pi['col'] . '", cmp:"' . $pi['cmp'] . '", val:"' . $pi['value'] . '"<br>';
			$this->model->add_predicate_item($pi['rel'], $pi['col'], $pi['cmp'], $pi['value']);
		}
		echo '<br>';
		echo $this->model->get_sql();
		echo '<hr>';

		// draw form
		$this->load->helper('form');
		$this->load->helper('filter');
		echo form_open("test/primary_filter/$config_name/$config_source");
		echo make_primary_filter($current_primary_filter_values);
		echo form_submit('mysubmit', 'Submit Post!');
		echo form_close();		
	}
	
	// --------------------------------------------------------------------
	// http://dmsdev.pnl.gov/test/secondary_filter
	// create a secondary filter object, 
	// initialize it to pull data from cache/POST
	// merge with information from associated model to get display-building info
	// and use it with rendering helper to make HTML for secondary filter
	function secondary_filter($config_name, $config_source) 
	{
		session_start();

//		$config_name = 'list_report';
//		$config_source = 'instrument';
		
		echo "Test of secondary filter <br>";
		echo 'config_name: ' . $config_name . '<br>';
		echo 'config_source: ' . $config_source . '<br>';
		echo '<hr>';

		echo 'POST:<br>';
		print_r($_POST);
		echo '<hr>';

		// it all starts with a model
		$this->load->model('q_model', 'model');
		$this->model->init($config_name, $config_source);
		
		// create secondary filter object and initialize with with data from cache/POST
		$this->load->library('secondary_filter');
		$this->secondary_filter->init($config_name, $config_source);

		echo 'Cached: (' . $this->secondary_filter->get_storage_name() . ')<br>';
		print_r($this->secondary_filter->get_cached_value());
		echo '<hr>';

		// apply filter items to model's predicate
		echo 'Predicate:<br>';
		$current_secondary_filter_values = $this->secondary_filter->get_current_filter_values();
		foreach($current_secondary_filter_values as $pi) {
			echo 'rel:"'. $pi['qf_rel_sel'] . '", col:"' . $pi['qf_col_sel'] . '", cmp:"' . $pi['qf_comp_sel'] . '", val:"' . $pi['qf_comp_val'] . '"<br>';
			$this->model->add_predicate_item($pi['qf_rel_sel'], $pi['qf_col_sel'], $pi['qf_comp_sel'], $pi['qf_comp_val']);
		}
		echo '<br>';
		echo $this->model->get_sql();
		echo '<hr>';

		// get display-building info for secondary filter merged with model information
		$sec_filter_display_info = $this->secondary_filter->collect_information_for_display($this->model);

		// display secondary filter in HTML table using rendering helper
		$this->load->helper('form');
		$this->load->helper('filter');
		echo form_open("test/secondary_filter/$config_name/$config_source");
		echo make_secondary_filter($sec_filter_display_info);
		echo form_submit('mysubmit', 'Submit Post!');
		echo form_close();
	}
	
	// --------------------------------------------------------------------
	// http://dmsdev.pnl.gov/test/refresh_cmp_selector_for_col/list_report/instrument/Name
	// AJAX refresh of comparison selector based on change in column selector 
	function refresh_cmp_selector_for_col($config_name, $config_source, $col) 
	{
		echo "Test of AJAX request to make comparison selector for column '$col' for '$config_name' query for '$config_source' config source.";
		echo '<hr>';
		session_start();

		$this->load->model('q_model', 'model');
		$this->model->init($config_name, $config_source);
		$data_type = $this->model->get_column_data_type($col);
		$cmpSelOpts = $this->model->get_allowed_comparisons_for_type($data_type);
		
		$this->load->helper('form');
		echo form_dropdown('qf_comp_sel[]', $cmpSelOpts);
	}

	// --------------------------------------------------------------------
	function sorting_filter($config_name, $config_source)
	{
		session_start();

		echo "Test of sorting filter <br>";
		echo 'config_name: ' . $config_name . '<br>';
		echo 'config_source: ' . $config_source . '<br>';
		echo '<hr>';

		echo 'POST:<br>';
		print_r($_POST);
		echo '<hr>';

		// it all starts with a model
		$this->load->model('q_model', 'model');
		$this->model->init($config_name, $config_source);

		$cols = $this->model->get_col_names();

		echo 'Data Columns:<br>';
		print_r($cols);
		echo '<hr>';
		
		// create sorting filter object and initialize with with data from cache/POST
		$this->load->library('sorting_filter');
		$this->sorting_filter->init($config_name, $config_source);
		$current_filter_values = $this->sorting_filter->get_current_filter_values();
		
		echo 'Cached: (' . $this->sorting_filter->get_storage_name() . ')<br>';
		print_r($this->sorting_filter->get_cached_value());
		echo '<hr>';
		
		echo 'Current filter values <br>';
		print_r($current_filter_values);
		echo '<hr>';

		$this->load->helper('form');
		$this->load->helper('filter');
		echo form_open("test/sorting_filter/$config_name/$config_source");
		echo make_sorting_filter($current_filter_values, $cols);
		echo form_submit('mysubmit', 'Submit Post!');
		echo form_close();
	}

	// --------------------------------------------------------------------
	function paging_filter($config_name, $config_source)
	{
		session_start();

		echo "Test of paging filter <br>";
		echo 'config_name: ' . $config_name . '<br>';
		echo 'config_source: ' . $config_source . '<br>';
		echo '<hr>';

		echo 'POST:<br>';
		print_r($_POST);
		echo '<hr>';

		// it all starts with a model
		$this->load->model('q_model', 'model');
		$this->model->init($config_name, $config_source);
		
		// create secondary filter object and initialize with with data from cache/POST
		$this->load->library('paging_filter');
		$this->paging_filter->init($config_name, $config_source);
		$current_filter_values = $this->paging_filter->get_current_filter_values();
		$cached_filter_values = $this->paging_filter->get_cached_value();

		echo 'Cached: (page size is remembered between page visits, but first row is set to 1)<br>';
		print_r($cached_filter_values);
		echo '<hr>';
		
		echo 'Current filter values <br>';
		print_r($current_filter_values);
		echo '<hr>';
		
		$this->load->helper('form');
		$this->load->helper('filter');
		echo form_open("test/paging_filter/$config_name/$config_source");
		echo make_paging_filter($current_filter_values);
		echo form_submit('mysubmit', 'Submit Post!');
		echo form_close();
	}
	
	// --------------------------------------------------------------------
	// integrated list report page tests
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	// list report main page
	function list_report($config_name, $config_source)
	{
		$this->my_tag = "test";

		$this->load->helper('url');
		
		// clear total rows cache in model to force getting value from database
		$this->load->model('q_model', 'model');
		$this->model->init($config_name, $config_source);
		$this->model->clear_cached_total_rows();

		$b_url = site_url() . $this->my_tag . '/';
		$p_url = "/$config_name/$config_source";

		$data['q_data_rows_ajax'] = $b_url . 'q_data_rows_ajax' . $p_url;	
		$data['q_data_rows_ajax_pages'] = $b_url . 'q_data_rows_ajax' . $p_url . '/total';
		$data['search_filter_ajax'] = $b_url . 'search_filter_ajax' . $p_url;
				
		$this->load->vars($data);		
		$this->load->view('test/list_report');
	}

	// --------------------------------------------------------------------
	// make search filter portion of list report
	// http://dmsdev.pnl.gov/test/search_filter_ajax/list_report/instrument
	// AJAX
	function search_filter_ajax($config_name, $config_source)
	{
		session_start();
		$this->load->helper('form');
		$this->load->helper('filter');

		$this->load->model('q_model', 'model');
		$this->model->init($config_name, $config_source);
		$cols = $this->model->get_col_names();
		
		$this->load->library('paging_filter');
		$this->paging_filter->init($config_name, $config_source);
		$current_paging_filter_values = $this->paging_filter->get_current_filter_values();
		
		$this->load->library('sorting_filter');
		$this->sorting_filter->init($config_name, $config_source);
		$current_sorting_filter_values = $this->sorting_filter->get_current_filter_values();
		
		$this->load->library('primary_filter');
		$pfs = $this->model->get_primary_filter_specs();
		$this->primary_filter->init($config_name, $config_source, $pfs);
		$current_primary_filter_values = $this->primary_filter->get_cur_filter_values();

		$this->load->library('secondary_filter');
		$this->secondary_filter->init($config_name, $config_source);
		$sec_filter_display_info = $this->secondary_filter->collect_information_for_display($this->model);

		make_search_filter_minimal($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values);
	}
	
	// --------------------------------------------------------------------
	// return data rows from database according to filter settings
	// http://dmsdev.pnl.gov/test/q_data_rows_ajax/list_report/instrument
	// AJAX
	function q_data_rows_ajax($config_name, $config_source, $option = 'rows')
	{
		session_start();
		
		// it all starts with a model
		$this->load->model('q_model', 'model');
		$this->model->init($config_name, $config_source);

		// primary filter
		$pfs = $this->model->get_primary_filter_specs();
		$this->load->library('primary_filter');
		$this->primary_filter->init($config_name, $config_source, $pfs);
		$current_primary_filter_values = $this->primary_filter->get_cur_filter_values();
		
		// secondary filter
		$this->load->library('secondary_filter');
		$this->secondary_filter->init($config_name, $config_source);
		$current_secondary_filter_values = $this->secondary_filter->get_current_filter_values();

		// paging filter		
		$this->load->library('paging_filter');
		$this->paging_filter->init($config_name, $config_source);
		$current_filter_values = $this->paging_filter->get_current_filter_values();

		
		foreach(array_values($current_primary_filter_values) as $pi) {
			$this->model->add_predicate_item($pi['rel'], $pi['col'], $pi['cmp'], $pi['value']);
		}
		foreach($current_secondary_filter_values as $pi) {
			$this->model->add_predicate_item($pi['qf_rel_sel'], $pi['qf_col_sel'], $pi['qf_comp_sel'], $pi['qf_comp_val']);
		}
		$this->model->add_paging_item($current_filter_values['qf_first_row'], $current_filter_values['qf_rows_per_page']);
		
		// if option is to return row count, take this path
		if($option == 'total') {
			$tr = $this->model->get_total_rows();
			$fr = $current_filter_values['qf_first_row'];
			$rp = $current_filter_values['qf_rows_per_page'];
			$lr = $fr + $rp - 1;
			$lr = ($lr > $tr)?$tr:$lr;
			echo "Rows $fr through $lr of $tr";
			return;
		}
				
		// sorting filter
		$this->load->library('sorting_filter');
		$this->sorting_filter->init($config_name, $config_source);
		$current_sorting_filter_values = $this->sorting_filter->get_current_filter_values();
		//
		foreach($current_sorting_filter_values as $item) {
				$this->model->add_sorting_item($item['qf_sort_col'], $item['qf_sort_dir']);
		}

		if(TRUE) {
			$rows = $this->model->get_rows();
			$this->load->library('table');
			$this->table->set_template(
				array (
					'table_open'    => '<table class="LRep">',  
					'row_start'     => '<tr class="ReportEvenRow">', 
					'row_alt_start' => '<tr class="ReportOddRow">'
				)
			); 
			echo $this->table->generate($rows);
		} else {
			$this->load->helper('test');
			dump_q_model($this->model);
		}
	}
	
	// --------------------------------------------------------------------
	// integrated detail report page tests
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	// detail report main page load
	function detail_report($config_name, $config_source, $id)
	{
		$this->my_tag = "test";
		
		$this->load->helper('url');
		
		$b_url = site_url() . $this->my_tag . '/';
		$p_url = "/$config_name/$config_source";

		$data['q_data_row_ajax'] = $b_url . 'q_data_row_ajax' . $p_url . '/' . $id;	
				
		$this->load->vars($data);	
		$this->load->view('test/detail_report');
	}

	// --------------------------------------------------------------------
	// for detail report data content
	// AJAX
	function q_data_row_ajax($config_name, $config_source, $id)
	{
		// it all starts with a model
		$this->load->model('q_model', 'model');
		$this->model->init($config_name, $config_source);

		$pfs = $this->model->get_primary_filter_specs();
		if(empty($pfs)) throw new exception('no primary id column defined');
		$spc = current($pfs);

		$this->model->add_predicate_item('AND', $spc['col'], $spc['cmp'], $id);
		
		$query = $this->model->get_rows('filtered_only');
		
		// get single row from results
		// and make table with a parameter/value row 
		// for each field in original row
		$result_row = $query->row_array();
		$details = array();
		foreach($result_row as $col => $val) {
			$display_row = array();
			$display_row['Parameter'] = $col;
			$display_row['Value'] = $val;
			$details[] = $display_row;
		}
		
//		echo 'SQL: <br>';
//		echo $this->model->get_main_sql();
//		echo '<br>';

		$this->load->library('table');
		$this->table->set_template(
			array (
				'table_open'    => '<table class="DRep">',  
				'row_start'     => '<tr class="ReportEvenRow">', 
				'row_alt_start' => '<tr class="ReportOddRow">'
			)
		); 
		$this->table->set_heading('Parameter', 'Value');
		echo $this->table->generate($details);
	}

	// --------------------------------------------------------------------
	// hotlinks
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	function r_model($config_source, $config_name = 'na')
	{
		echo 'howdy';
		$this->load->library('controller_utility', '', 'cu');
		$this->cu->load_mod('r_model', 'link_model', $config_name, $config_source);

		$this->load->helper('test');

		dump_r_model($this->link_model);
	}
	
	// --------------------------------------------------------------------
	// integrated entry page tests
	// --------------------------------------------------------------------

	// --------------------------------------------------------------------
	// entry form main page load
	function entry($config_name, $config_source, $id)
	{
		$this->my_tag = "test";
		
		$this->load->helper('url');
		
		$b_url = site_url() . $this->my_tag . '/';
		$p_url = "/$config_name/$config_source";

		$data['q_data_row_ajax'] = $b_url . 'q_data_row_ajax' . $p_url . '/' . $id;	
				
		$this->load->vars($data);	
		$this->load->view('test/entry_form');
	}

	// --------------------------------------------------------------------
	// entry form main submit
	// AJAX
	function entry_submit($config_name, $config_source)
	{
		$this->load->helper('test');
		$this->load->helper('user');
		
		$this->load->model('e_model', 'model');
		$this->model->init($config_name, $config_source);
		$which_ones = array('fields', 'rules');
		$form_def = $mod->get_form_def($which_ones);
		
		// make validation object and use it to 
		// get field values from POST and validate them
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="bad_clr">', '</span>');
		$this->form_validation->set_rules($form_def->rules);
		$valid_fields = $this->form_validation->run();
		
		// get field values from validation objec into an object
		// that will be used for calling stored procedure
		// and also putting values back into entry form HTML 
		// returned by this function
		$calling_params = new stdClass();
		foreach($form_def->fields as $field) {
			$calling_params->$field = set_value($field);
		}
		// magic parameters for calling sproc
		$calling_params->mode = $this->input->post('entry_cmd_mode');
		$calling_params->callingUser = get_user();
		
		try {
			if (!$valid_fields) {
				throw new exception('There were validation errors');
			}
			
			// call stored procedure		
			$this->load->model('s_model', 'sproc_model');
			$ok = $this->sproc_model->init('entry', $config_source);
			if(!$ok) throw new exception($this->sproc_model->get_error_text());
			//
//			$ok = $this->sproc_model->execute_sproc($calling_params);
			if(!$ok) throw new exception($this->sproc_model->get_error_text());

			// everything worked - compose tidings of joy
			$message = 'Operation was successful';
			$outcome = entry_outcome_message($message);
			$supplement = entry_outcome_message($message, 'normal');
		} catch (Exception $e) {
			// something broke - compose expressions of regret
			$message = $e->getMessage();
			$outcome = entry_outcome_message($message, 'failure');
			$supplement = entry_outcome_message($message, 'error');
		}

		// entry form object
		$form_def = $mod->get_form_def(array('specs'));
		
		$this->load->library('entry_form');
		$this->entry_form->init($form_def->specs, $config_source);
		//
		// update entry form object with field values 
		// and any field validation errors
		foreach($form_def->fields as $field) {
//			$this->entry_form->set_field_value($field, set_value($field));
			$this->entry_form->set_field_value($field, $calling_params->$field);
			$this->entry_form->set_field_error($field, form_error($field));
		}
		$data['form'] = $this->entry_form->build_display();
		echo $outcome;
		echo $data['form'];
		echo $supplement;
	}

	// --------------------------------------------------------------------
	// entry model tests
	function e_model($config_name, $config_source)
	{
		$this->load->helper('test');
		$this->load->helper('url');

		$this->load->model('e_model', 'model');
		$this->model->init($config_name, $config_source);

		dump_e_model($this->model);
	}

	// --------------------------------------------------------------------
	// entry model tests
	function entry_model($config_name, $config_source)
	{
		$this->load->helper('test');
		$this->load->helper('url');

		$this->load->model('e_model', 'model');
		$this->model->init($config_name, $config_source);

		dump_e_model($this->model);
		
		$form_def = $this->model->get_form_def(array('specs'));
		
		// entry form object
		$this->load->library('entry_form');
		$this->entry_form->init($form_def->specs, $config_source);
		
		$data['form'] = $this->entry_form->build_display();
				
		$data['entry_form_submit_url'] = site_url(). "test/entry_submit/na/$config_source";

		$this->load->vars($data);	
		$this->load->view('test/entry_form');
	}
	// --------------------------------------------------------------------
	// entry model tests
	function entry_model_dump($config_name, $config_source)
	{
		$this->load->helper('test');
		$this->load->helper('url');

		$this->load->model('e_model', 'model');
		$this->model->init($config_name, $config_source);

		dump_e_model($this->model);
	
		$form_def = $this->model->get_form_def(array('fields', 'rules', 'specs', 'load_key', 'enable_spec', 'entry_commands'));
		
		if(property_exists ($form_def, 'fields')) {
			echo 'fields'; echo ":<br>"; echo json_encode($form_def->fields ); echo '<hr>';
		}
		if(property_exists ($form_def, 'rules')) {
			echo 'rules'; echo ":<br>"; echo json_encode($form_def->rules ); echo '<hr>';
		}	
		if(property_exists ($form_def, 'specs')) {
			echo 'specs'; echo ":<br>"; echo json_encode($form_def->specs ); echo '<hr>';
		}	
		if(property_exists ($form_def, 'load_key')) {
			echo 'load_key'; echo ":<br>"; echo json_encode($form_def->load_key ); echo '<hr>';
		}	
		if(property_exists ($form_def, 'enable_spec')) {
			echo 'enable_spec'; echo ":<br>"; echo json_encode($form_def->enable_spec ); echo '<hr>';
		}
		if(property_exists ($form_def, 'entry_commands')) {
			echo 'entry_commands'; echo ":<br>"; echo json_encode($form_def->entry_commands ); echo '<hr>';
		}		
				
	}
	
	// --------------------------------------------------------------------
	// 
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	function g_model($config_source, $user = 'd3j410')
	{

		$this->load->model('g_model', 'gen_model');
		$this->gen_model->init('na', $config_source);

		$action = 'enter';
		echo $action . ' -> ' . $this->gen_model->check_permission($action, $user, $config_source) . '<br>';
		$action = 'operation';
		echo $action . ' -> ' . $this->gen_model->check_permission($action, $user, $config_source) . '<br>';
		$action = 'report';
		echo $action . ' -> ' . $this->gen_model->check_permission($action, $user, $config_source) . '<br>';
		$action = 'show';
		echo $action . ' -> ' . $this->gen_model->check_permission($action, $user, $config_source) . '<br>';
		$action = 'param';
		echo $action . ' -> ' . $this->gen_model->check_permission($action, $user, $config_source) . '<br>';
		$action = 'export';
		echo $action . ' -> ' . $this->gen_model->check_permission($action, $user, $config_source) . '<br>';
		
		echo 'ha';
	}

	// --------------------------------------------------------------------
	// spreadsheet loader
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	function load($fname = 'Experiment_upload.txt')
	{
		$this->load->library('spreadsheet_loader');
		$this->spreadsheet_loader->load($fname);
		
		// table dump
		$this->load->library('table');
//		$this->table->set_template(array ('table_open'  => '<table border="1" cellpadding="2" cellspacing="2">'));
		echo $this->table->generate($this->spreadsheet_loader->get_extracted_data());
	}
	// --------------------------------------------------------------------
	// misc
	// --------------------------------------------------------------------

	// --------------------------------------------------------------------
	function pager()
	{
		echo '<h3>Pager test</h3>';
		$this->load->model('dms_preferences', 'preferences');
		$this->load->library(array('list_report_pager'));

		echo "<div>Setup tests</div>\n";
		$this->list_report_pager->set(1, 72, 13);
		$this->list_report_pager->set(15, 33, 7);
		$this->list_report_pager->set(42, 33, 7);
		echo '<hr><hr>';

		echo "<div>Paging tests</div>\n";
		$total_rows = 101;
		$per_page = 10;
		$num_pages = ceil((int) $total_rows / (int) $per_page);
		echo "<div>total_rows:$total_rows per_page:$per_page</div>\n";
		echo '<hr>';
		for($page = 1; $page <= $num_pages; $page++) {
			$first_row = (($page -1) * $per_page) + 1;
			
			echo '<hr>'; 
			$this->list_report_pager->set($first_row, $total_rows, $per_page);
			$pr = $this->list_report_pager->create_links();
			$ps = $this->list_report_pager->create_stats();
			echo "<div>$ps &nbsp; &nbsp; $pr </div>\n";
			
		}		
	}
	
	// --------------------------------------------------------------------
	function general()
	{
		$this->load->helper('url');
		$this->load->view('test/general');
	}

		// --------------------------------------------------------------------
	function web_services_client()
	{
		$this->load->helper('url');
		$this->load->view('test/web_service_client');
	}
	
}
?>