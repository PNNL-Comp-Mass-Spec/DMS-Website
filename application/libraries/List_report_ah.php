<?php
require("List_report.php");

/**
 * Generate a list report from queries defined in the utility_queries table
 */
class List_report_ah extends List_report {

	// --------------------------------------------------------------------
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Make a list report page
	 * (override of base class function)
	 * @param string $mode
	 */
	function list_report($mode)
	{
		$CI = &get_instance();
		session_start();
		$CI->load->helper(array('form', 'menu', 'link_util'));
		$CI->load->model('dms_chooser', 'choosers');

		$CI->cu->load_mod('g_model', 'gen_model', $this->config_name, $this->config_source);
		$CI->cu->load_mod('r_model', 'link_model', $this->config_name, $this->config_source);
		
		// clear total rows cache in model to force getting value from database
		$CI->cu->load_mod('q_model', 'model', $this->config_name, $this->config_source);
		$CI->model->clear_cached_total_rows();

 		// if there were extra segments for list report URL, 
 		// convert them to primary filter field values and cache those
 		// and redirect back to ourselves without the trailing URL segments
		$all_segs = $CI->uri->segment_array();
		$end_of_root_segs = array_search($mode, $all_segs);
		$root_segs = array_slice($all_segs, 0, $end_of_root_segs);
		$segs = array_slice($all_segs, $end_of_root_segs);
		if(!empty($segs)) {
			$primary_filter_specs = $CI->model->get_primary_filter_specs();
			$this->set_pri_filter_from_url_segments($segs, $primary_filter_specs);
			redirect(implode('/', $root_segs));
		}	

		$data['tag'] = $this->tag;
		$data['title'] = $CI->gen_model->get_page_label('', $mode);

		// get stuff related to list report optional features
		$data['loading'] = ($mode === 'search')?'no_load':'';
		$data['list_report_cmds'] = ''; ///$CI->gen_model->get_param('list_report_cmds');
		$data['is_ms_helper'] = $CI->gen_model->get_param('is_ms_helper');
		$data['has_checkboxes'] = $CI->gen_model->get_param('has_checkboxes');
		$data['ops_url'] = ''; ///site_url() . $CI->gen_model->get_param('list_report_cmds_url');		

		$data['nav_bar_menu_items']= set_up_nav_bar('List_Reports');
		$CI->load->vars($data);		
		$CI->load->view('main/list_report');
	}

	/**
	 * Returns HTML displaying the list report data rows
	 * for inclusion in list report page
	 * (override of base class function)
	 * @param type $option
	 * @category AJAX
	 */
	function report_data($option = 'rows')
	{
		$CI = &get_instance();
		// preemptively load the hotlinks model from the ad hoc config db 
		// to prevent parent from loading it from general_param table, 
		// then let parent handle it
		$CI->cu->load_mod('r_model', 'link_model', $this->config_name, $this->config_source);
		parent::report_data($option);
	}

	// --------------------------------------------------------------------
	function set_up_data_query()
	{
		$this->set_up_list_query();		
	}
	
}
