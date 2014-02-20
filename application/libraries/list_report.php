<?php
// --------------------------------------------------------------------
// list report page section
// --------------------------------------------------------------------

class List_report {

	protected $config_source = '';
	protected $config_name = '';

	protected $tag = '';
	protected $title = '';
	
	// --------------------------------------------------------------------
	function __construct()
	{
	}
	
	// --------------------------------------------------------------------
	function init($config_name, $config_source)
	{
		$this->config_name = $config_name;
		$this->config_source = $config_source;

		$CI = &get_instance();
		$this->tag = $CI->my_tag;
		$this->title = $CI->my_title;
	}

	// --------------------------------------------------------------------
	// make list report page
	//
	function list_report($mode)
	{
		$CI = &get_instance();
		session_start();
		$CI->load->helper(array('form', 'menu', 'link_util'));
		$CI->load->model('dms_chooser', 'choosers');
				
		$CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);
		
		// clear total rows cache in model to force getting value from database
		$CI->cu->load_mod('q_model', 'model', $this->config_name, $this->config_source);
		$CI->model->clear_cached_total_rows();

 		// if there were extra segments for list report URL, 
 		// convert them to primary filter field values and cache those
 		// and redirect back to ourselves without the trailing URL segments
 		$segs = array_slice($CI->uri->segment_array(), 2);
		if(!empty($segs)) {
			$primary_filter_specs = $CI->model->get_primary_filter_specs();
			$this->set_pri_filter_from_url_segments($segs, $primary_filter_specs);
			redirect($this->tag.'/'.$mode);
		}	
	
		$data['tag'] = $this->tag;

		$data['title'] = $CI->gen_model->get_page_label($this->title, $mode);

		// get stuff related to list report optional features
		$data['loading'] = ($mode == 'search')?'no_load':'';
		$data['list_report_cmds'] = $CI->gen_model->get_param('list_report_cmds');
		$data['is_ms_helper'] = $CI->gen_model->get_param('is_ms_helper');;
		$data['has_checkboxes'] = $CI->gen_model->get_param('has_checkboxes');
		$data['ops_url'] = site_url() . $CI->gen_model->get_param('list_report_cmds_url');		
		
		$data['nav_bar_menu_items']= set_up_nav_bar('List_Reports');
		$CI->load->vars($data);		
		$CI->load->view('main/list_report');
	}

	// --------------------------------------------------------------------
	// need to initialize primary filter values from URL segments
	// and cache them for subsequent queries
	protected
	function set_pri_filter_from_url_segments($segs, $primary_filter_specs)
	{
		$CI = &get_instance();
		
		// primary filter object (we will use it to cache field values)
		$CI->cu->load_lib('primary_filter', $this->config_name, $this->config_source, $primary_filter_specs);

		// get list of just the names of primary filter fields
		$form_field_names = array_keys($primary_filter_specs);

		// use entry page helper mojo to relate segments to filter fields
		$CI->load->helper(array('entry_page')); 
		$initial_field_values = get_values_from_segs($form_field_names, $segs);
		
		// we are completely replacing filter values, so get rid of any we pulled from cache
		$CI->primary_filter->clear_current_filter_values();

		// update values in primary filter object
		foreach($initial_field_values as $field => $value) {
			$CI->primary_filter->set_current_filter_value($field, $value);
		}
		// and cache the values we got from the segments
		$CI->primary_filter->save_current_filter_values();
	}
	
	// --------------------------------------------------------------------
	// make filter section for list report page:
	// returns HTML containing filter components arranged in the specified format
	// AJAX
	function report_filter($filter_display_mode = 'advanced')
	{
		$CI = &get_instance();
		session_start();

		$CI->load->helper('form');
		$CI->load->helper(array('filter', 'link_util'));

		$CI->cu->load_mod('q_model', 'data_model', $this->config_name, $this->config_source);
		$cols = $CI->data_model->get_col_names();
		
		$CI->cu->load_lib('paging_filter', $this->config_name, $this->config_source);
		$current_paging_filter_values = $CI->paging_filter->get_current_filter_values();
		
		$CI->cu->load_lib('sorting_filter', $this->config_name, $this->config_source);
		$current_sorting_filter_values = $CI->sorting_filter->get_current_filter_values();
		
		$CI->cu->load_lib('column_filter', $this->config_name, $this->config_source);
		$col_filter = $CI->column_filter->get_current_filter_values();

		$primary_filter_specs = $CI->data_model->get_primary_filter_specs();
		$CI->cu->load_lib('primary_filter', $this->config_name, $this->config_source, $primary_filter_specs);
		$current_primary_filter_values = $CI->primary_filter->get_cur_filter_values();

		$CI->cu->load_lib('secondary_filter', $this->config_name, $this->config_source);
		$sec_filter_display_info = $CI->secondary_filter->collect_information_for_display($CI->data_model, "$this->config_source/get_sql_comparison/");

		switch($filter_display_mode) {
			case 'minimal':
				make_search_filter_minimal($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter);
				break;
			case 'maximal':
				make_search_filter_expanded($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter);
				break;
			case 'intermediate':
				make_search_filter_expanded($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter, $filter_display_mode);
				break;
		}
	}

	// --------------------------------------------------------------------
	// returns the HTML for a query filter comparison field selector 
	// for the given column name
	// AJAX
	function get_sql_comparison($column_name) 
	{
		$CI = &get_instance();
		session_start();

		$CI->cu->load_mod('q_model', 'model', $this->config_name, $this->config_source);
		$data_type = $CI->model->get_column_data_type($column_name);
		$cmpSelOpts = $CI->model->get_allowed_comparisons_for_type($data_type);
		
		$CI->load->helper('form');
		echo form_dropdown('qf_comp_sel[]', $cmpSelOpts);
	}
	
	// --------------------------------------------------------------------
	// returns HTML displaying the list report data rows
	// for inclusion in list report page
	// AJAX
	function report_data($option = 'rows')
	{
		$CI = &get_instance();
		session_start();
		
		$this->set_up_list_query();

		$CI->cu->load_mod('r_model', 'link_model', 'na', $this->config_source);
		
		$CI->cu->load_lib('column_filter', $this->config_name, $this->config_source);
		$col_filter = $CI->column_filter->get_current_filter_values();

		$CI->load->library('cell_presentation');
		$CI->cell_presentation->init($CI->link_model->get_list_report_hotlinks());
		$CI->cell_presentation->set_col_filter($col_filter);

		$rows = $CI->data_model->get_rows()->result_array();
		if(empty($rows)) {
			echo "<div id='data_message' >No rows found</div>";
		} else {
			$col_info = $CI->data_model->get_column_info();
			$CI->cell_presentation->fix_datetime_display($rows, $col_info);
	
			$qp = $CI->data_model->get_query_parts();
			$data['row_renderer'] = $CI->cell_presentation;
			$data['column_header'] = $CI->cell_presentation->make_column_header($rows, $qp->sorting_items);
			$data['rows'] = $rows;	
			
			$CI->load->helper(array('string'));
			$CI->load->vars($data);	
			$CI->load->view('main/list_report_data');
		}
	}

	// --------------------------------------------------------------------
	// returns HTML displaying the list report data rows
	// for inclusion in list report page
	// AJAX
	function report_info($what_info)
	{
		$CI = &get_instance();
		session_start();
		$this->set_up_list_query();
		
		switch($what_info) {
			case "sql":
				echo $CI->data_model->get_sql("filtered_and_sorted");
				break;
			case "url":
				$filters = $this->set_up_list_query();
				echo $this->dump_filters($filters, $CI->my_tag);
				break;
		}
	}
	///------
	private 
	function dump_filters($filters, $tag)
	{
		$s = "";
		// primary
		$zz = array();
		foreach($filters["primary"] as $f) {
			$zz[] = ($f["value"]) ? $f["value"] : "-" ;
		}
		$s .= site_url() . "$tag/report/" . implode("/", $zz);
		// secondary
		$sf = array();
		foreach($filters["secondary"] as $f) {
			if($f["qf_comp_val"]) {
				$sf[] = $f["qf_rel_sel"] . "|" .$f["qf_col_sel"] . "|" .$f["qf_comp_sel"] . "|" . $f["qf_comp_val"];
			}
		}
		if(!empty($sf)) {
			$s .= "<br><hr>Secondary:<br>" . implode("<br>", $sf);
		}
		return $s;
	}
	///-----
	
	// --------------------------------------------------------------------
	// returns HTML for the paging display and control element 
	// for inclusion in report pages
	// AJAX
	function report_paging()
	{
		$CI = &get_instance();
		session_start();

    	$CI->load->helper(array('link_util'));
		$this->set_up_list_query();

		$current_filter_values = $CI->paging_filter->get_current_filter_values();

		// pull together info necessary to do paging displays and controls
		// and use it to set up a pager object
		$CI->load->model('dms_preferences', 'preferences');
		$CI->load->library(array('list_report_pager'));
		try {	
			// make HTML using pager
			$s = '';
			$total_rows = $CI->data_model->get_total_rows();;
			$per_page = $current_filter_values['qf_rows_per_page'];
			$first_row = $current_filter_values['qf_first_row'];
			$CI->list_report_pager->set($first_row, $total_rows, $per_page);
			$pr = $CI->list_report_pager->create_links();
			$ps = $CI->list_report_pager->create_stats();
			
			$s .= "<span class='LRepPager'>$ps</span>";
			$s .= "<span class='LRepPager'>$pr</span>";
			echo $s;		
		} catch (Exception $e) {
			echo "Paging controls could not be built.  " . $e->getMessage();
		}
		
	}
	
	// --------------------------------------------------------------------
	// set up query for database entity based on list report filtering
	protected
	function set_up_list_query()
	{
		$CI = &get_instance();
		// it all starts with a model
		$CI->cu->load_mod('q_model', 'data_model', $this->config_name, $this->config_source);

		// primary filter
		$primary_filter_specs = $CI->data_model->get_primary_filter_specs();
		$CI->cu->load_lib('primary_filter', $this->config_name, $this->config_source, $primary_filter_specs);
		$current_primary_filter_values = $CI->primary_filter->get_cur_filter_values();
		
		// secondary filter
		$CI->cu->load_lib('secondary_filter', $this->config_name, $this->config_source);
		$current_secondary_filter_values = $CI->secondary_filter->get_current_filter_values();

		// paging filter		
		$CI->cu->load_lib('paging_filter', $this->config_name, $this->config_source);
		$current_filter_values = $CI->paging_filter->get_current_filter_values();

		// sorting filter
		$CI->cu->load_lib('sorting_filter', $this->config_name, $this->config_source);
		$current_sorting_filter_values = $CI->sorting_filter->get_current_filter_values();

		// add filter values to data model to set up query
		foreach(array_values($current_primary_filter_values) as $pi) {
			$CI->data_model->add_predicate_item($pi['rel'], $pi['col'], $pi['cmp'], $pi['value']);
		}
		foreach($current_secondary_filter_values as $pi) {
			$CI->data_model->add_predicate_item($pi['qf_rel_sel'], $pi['qf_col_sel'], $pi['qf_comp_sel'], $pi['qf_comp_val']);
		}
		foreach($current_sorting_filter_values as $item) {
				$CI->data_model->add_sorting_item($item['qf_sort_col'], $item['qf_sort_dir']);
		}
		$CI->data_model->add_paging_item($current_filter_values['qf_first_row'], $current_filter_values['qf_rows_per_page']);
		
		$CI->data_model->convert_wildcards();
		
		// return filter settings
		return array(
			"primary" => $current_primary_filter_values,
			"secondary" => $current_secondary_filter_values
		);
	}
	
	
	// --------------------------------------------------------------------
	// export list report
	function export($format)
	{
		$CI = &get_instance();
		session_start();
		$CI->load->helper(array('export'));
	
		$this->set_up_list_query();

		$CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);

		$data['title'] = $CI->gen_model->get_page_label($this->title, 'export');

		$rows = $CI->data_model->get_rows('filtered_and_sorted')->result_array();
		
		$CI->load->library('cell_presentation');
		$col_info = $CI->data_model->get_column_info();
		$CI->cell_presentation->fix_datetime_display($rows, $col_info);
		
		$CI->cu->load_lib('column_filter', $this->config_name, $this->config_source);
		$col_filter = $CI->column_filter->get_current_filter_values();
		
// (someday) list report document export - output helper needs to clean out newlines and so forth.
		if (empty($rows)) {
		  echo '<p>The table appears to have no data.</p>';
		} else {
			switch($format) {
				case 'excel':
					export_to_excel($rows, $this->tag, $col_filter);
					break;
				case 'tsv':
					export_to_tab_delimited_text($rows, $this->tag, $col_filter);
					break;
			case 'json':
				header("Content-type: application/json");
				echo json_encode($rows);
				break;
			}
		}
	}
	
}
?>