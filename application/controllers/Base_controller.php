<?php

class Base_controller extends CI_Controller {

	var $my_tag = "";

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->library('controller_utility', '', 'cu');
	}

	// --------------------------------------------------------------------
	function index()
	{
		redirect($this->my_tag.'/report');
	}
	
	// --------------------------------------------------------------------
	// entry page section
	// --------------------------------------------------------------------
	
	/**
	 * Create an entry page to make a new record in the database
	 */
	function create()
	{
		$page_type = 'create';
		if (!$this->cu->check_access('enter')) {
			return;
		}
		$this->cu->load_lib('entry', 'na', $this->my_tag);
		$this->entry->create_entry_page($page_type);
	}

	/**
	 * Create an entry page to edit an existing record in the database
	 * @param type $id
	 * @return type
	 */
	function edit($id = '')
	{
		if(!$id) {
			$this->cu->message_box('Edit Error', 'No object ID was given');
			return;
		}
		$page_type = 'edit';
		if (!$this->cu->check_access('enter')) {
			return;
		}
		$this->cu->load_lib('entry', 'na', $this->my_tag);
		$this->entry->create_entry_page($page_type);
	}

	/**
	 * Create or update entry in database from entry page form fields in POST:
	 * @category AJAX
	 */
	function submit_entry_form()
	{
		if (!$this->cu->check_access('enter')) {
			return;
		}
		$this->cu->load_lib('entry', 'na', $this->my_tag);
		$this->entry->submit_entry_form();
	}

	// --------------------------------------------------------------------
	// list report page section
	// --------------------------------------------------------------------
	
	/**
	 * action for "report" format of list report
	 */
	function report()
	{
		if (!$this->cu->check_access('report')) {
			return;
		}
		$this->cu->load_lib('list_report', 'list_report', $this->my_tag);
		$this->list_report->list_report('report');
		return;
	}

	/**
	 * Action for "search" version of list report
	 */
	function search()
	{
		if (!$this->cu->check_access('report')) {
			return;
		}
		$this->cu->load_lib('list_report', 'list_report', $this->my_tag);
		$this->list_report->list_report('search');
		return;
	}

	/**
	 * Make filter section for list report page:
	 * Returns HTML containing filter components arranged in the specified format
	 * @param string $filter_display_mode
	 * @category AJAX
	 */
	function report_filter($filter_display_mode = 'advanced')
	{
		$this->cu->load_lib('list_report', 'list_report', $this->my_tag);
		$this->list_report->report_filter($filter_display_mode);
	}

	/**
	 * Returns the HTML for a query filter comparison field selector for the given column name
	 * @param type $column_name
	 * @category AJAX
	 */
	function get_sql_comparison($column_name) 
	{
		$this->cu->load_lib('list_report', 'list_report', $this->my_tag);
		$this->list_report->get_sql_comparison($column_name);
	}
	
	/**
	 * Returns HTML displaying the list report data rows for inclusion in list report page
	 * @param type $option
	 * @category AJAX
	 */
	function report_data($option = 'rows')
	{
		$this->cu->load_lib('list_report', 'list_report', $this->my_tag);
		$this->list_report->report_data($option);
	}
	
	/**
	 * Returns HTML displaying supplemental information about page for inclusion in list report page
	 * @param type $what_info
	 * @category AJAX
	 */
	function report_info($what_info)
	{
		$this->cu->load_lib('list_report', 'list_report', $this->my_tag);
		$this->list_report->report_info($what_info);
	}
	
	/**
	 * returns HTML for the paging display and control element for inclusion in report pages
	 * @category AJAX
	 */
	function report_paging()
	{
		$this->cu->load_lib('list_report', 'list_report', $this->my_tag);
		$this->list_report->report_paging();
	}

	/**
	 * Export list report
	 * @param string $format
	 */
	function export($format)
	{
		$this->cu->load_lib('list_report', 'list_report', $this->my_tag);
		$this->list_report->export($format);
	}

	// --------------------------------------------------------------------
	// detail report page section
	// --------------------------------------------------------------------

	// --------------------------------------------------------------------
	function show($id)
	{	
		if (!$this->cu->check_access('show')) {
			return;
		}
		$this->cu->load_lib('detail_report', 'detail_report', $this->my_tag);
		$this->detail_report->detail_report($id);
	}
	
	/**
	 * Show the data with minimal formatting (no headers, but does have "Edit, Copy and New")
	 * For example http://dms2.pnl.gov/param_file/show_data/3287
	 * Actual data loading occurs in method detail_report_data in file Detail_report.php
	 * @param type $id
	 */
	function show_data($id)
	{
		$this->cu->load_lib('detail_report', 'detail_report', $this->my_tag);
		$this->detail_report->detail_report_data($id);
	}

	/**
	 * Make a page to show a detailed report for the single record identified by the the user-supplied id
	 * Typically accessed using a call like http://dms2.pnl.gov/param_file/show/3287
	 * @param string $id
	 * @return type
	 */
	function detail_report($id)
	{
		if (!$this->cu->check_access('show')) {
			return;
		}
		$this->cu->load_lib('detail_report', 'detail_report', $this->my_tag);
		$this->detail_report->detail_report($id);		
	}

	/**
	 * Get detail report data for specified entity
	 * @param string $id
	 * @category AJAX
	 */
	function detail_report_data($id)
	{
		$show_entry_links = $this->cu->check_access('enter', FALSE);
		$this->cu->load_lib('detail_report', 'detail_report', $this->my_tag);
		$this->detail_report->detail_report_data($id, $show_entry_links);
	}

	/**
	 * Returns SQL for detail report
	 * @param string $id
	 * @category AJAX
	 */
	function detail_sql($id)
	{
		$this->cu->load_lib('detail_report', 'detail_report', $this->my_tag);
		$this->detail_report->detail_sql($id);
	}

	/**
	 * Get aux info controls associated with specified entity
	 * @param type $id
	 * @category AJAX
	 */
	function detail_report_aux_info_controls($id)
	{
		$this->cu->load_lib('detail_report', 'detail_report', $this->my_tag);
		$this->detail_report->detail_report_aux_info_controls($id);
	}

	/**
	 * Export detailed report for the single record identified by the the user-supplied id
	 * @param string $id
	 * @param string $format
	 */
	function export_detail($id, $format)
	{
		$this->cu->load_lib('detail_report', 'detail_report', $this->my_tag);
		$this->detail_report->export_detail($id, $format);
	}

	/**
	 * Export spreadsheet template for the single record identified by the the user-supplied id
	 * @param string $id
	 * @param string $format
	 */
	function export_spreadsheet($id, $format)
	{
		$this->cu->load_lib('detail_report', 'detail_report', $this->my_tag);
		$this->detail_report->export_spreadsheet($id, $format);
	}

	/**
	 * Display contents of given script as graph
	 * @param string $scriptName
	 */
	function dot($scriptName)
	{ $this->my_tag;
		$this->cu->load_lib('detail_report', 'detail_report', $this->my_tag);
		$this->detail_report->dot($scriptName, $this->my_tag);
	}
	
	// --------------------------------------------------------------------
	// param report (stored procedure based list report) section
	// --------------------------------------------------------------------
	
	/**
	 * Sets up a page that contains an entry form defined by the
	 * E_model for the config db which will be used to get data
	 * rows in HTML via and AJAX call to the param_data function.
	 * @return type
	 */
	function param()
	{
		if (!$this->cu->check_access('param')) {
			return;
		}
		$this->cu->load_lib('param_report', 'list_report_sproc', $this->my_tag);
		$this->param_report->param();
	}

	/**
	 * Returns HTML data row table of data returned by stored procedure
	 * @return type
	 * @category AJAX
	 */
	function param_data()
	{
		if (!$this->cu->check_access('param')) {
			return;
		}
		$this->cu->load_lib('param_report', 'list_report_sproc', $this->my_tag);
		$this->param_report->param_data();
	}

	/**
	 * Returns HTML for the paging display and control element 
	 * for inclusion in param report pages
	 * @category AJAX
	 */
	function param_paging()
	{
		$this->cu->load_lib('param_report', 'list_report_sproc', $this->my_tag);
		$this->param_report->param_paging();
	}
	
	/**
	 * Returns HTML for defining custom filters
	 * @category AJAX
	 */
	function param_filter()
	{
		$this->cu->load_lib('param_report', 'list_report_sproc', $this->my_tag);
		$this->param_report->param_filter();
	}
	// --------------------------------------------------------------------
	// export param report
	function export_param($format)
	{
		if (!$this->cu->check_access('param')) {
			return;
		}
		$this->cu->load_lib('param_report', 'list_report_sproc', $this->my_tag);
		$this->param_report->export_param($format);
	}

	// --------------------------------------------------------------------
	// 'operations' style stored procedure functions section
	// --------------------------------------------------------------------
	
	/**
	 * Invokes the stored procedure given by $sproc_name and returns simple JSON response.
	 * @param type $sproc_name
	 * @category AJAX
	 */
	function call($sproc_name = 'operations_sproc')
	{
		$this->cu->load_lib('operation', 'na', $this->my_tag);
		$response = $this->operation->internal_operation($sproc_name);
//		$response->parms = $this->operation->get_params();
		echo json_encode($response);
	}
		
	
	/**
	 * Invokes the stored procedure given by $sproc_name and returns simple JSON response.
	 * (someday) allow name of stored procedure to be passed as part of POST
	 * @param type $sproc_name
	 * @category AJAX
	 */
	function exec($sproc_name = 'operations_sproc')
	{
//		if(!$this->cu->check_access('??')) return;
//		$sproc_name = $this->uri->segment(3, '');
		$this->cu->load_lib('operation', 'na', $this->my_tag);
		$response = $this->operation->internal_operation($sproc_name);
		if($response->result == 0) {
			if (empty($response->message))
				$response->message = "Operation was successful";
			else
				$response->message = "Operation was successful: " . $response->message;
		} else {
			$response->message = "Update failed: " . $response->message;
		}
		echo json_encode($response);
	}

	/**
	 * Invokes the model's 'operation' stored procedure and returns simple text response.
	 * @return type
	 * @category AJAX
	 */
	function operation()
	{
		if (!$this->cu->check_access('operation')) {
			return;
		}
		$this->cu->load_lib('operation', 'na', $this->my_tag);
		$response = $this->operation->internal_operation('operations_sproc');
		if($response->result != 0) {
			echo "Update failed. " . $response->message;
		} else {
			if (empty($response->message))
				echo "Operation was successful (row refresh may be required).";
			else
				echo "Operation was successful: " . $response->message;
		}
	}

	/**
	 * Invokes the model's 'operation' stored procedure and returns simple text response.
	 * This is a thin wrapper over the internal function "internal_operation"
	 * @return type
	 * @category AJAX
	 */
	function command()
	{
		if (!$this->cu->check_access('operation')) {
			return;
		}
		$this->cu->load_lib('operation', 'na', $this->my_tag);
		$response = $this->operation->internal_operation('operations_sproc');
		if($response->result != 0) {
			echo "Update failed. " . $response->message;
		} else {
			if (empty($response->message))
				echo "Operation was successful";
			else
				echo "Operation was successful: " . $response->message;
		}
	}

	// --------------------------------------------------------------------
	// miscelleneous section
	// --------------------------------------------------------------------
	
	// --------------------------------------------------------------------
	function get_basic_nav_bar_items()
	{
		$this->load->helper(array('user', 'dms_search', 'menu'));
		$this->load->model('dms_menu', 'menu', TRUE);
		return get_nav_bar_menu_items('');
	}

	// --------------------------------------------------------------------
	// http://dmsdev.pnl.gov/controller/data/<output format>/<query name>/<filter value>/.../<filter value>
	// --------------------------------------------------------------------
	function data()
	{
		session_start();
		$this->load->library('controller_utility', '', 'cu');
		$this->cu->load_lib('general_query', '', ''); // $config_name, $config_source
		$input_parms = $this->general_query->setup_query_for_base_controller();
		$this->general_query->output_result($input_parms->output_format);		
	}
	
	/**
	 * Clears cached session variables
	 * (someday) handle param reports?
	 * @param type $page_type
	 * @category AJAX
	 */
	function defaults($page_type) //'Param_Pages''list_report_sproc'   'list_report'
	{
		$this->load->library('saved_settings');
		$this->saved_settings->defaults($page_type, $this->my_tag);
	}

	/**
	 * RSS Feed (not implemented)
	 */
	function rss()
	{
		// (someday) make RSS export work or remove it
		echo "This is not implemented";
	}

	/**
	 * Set custom list of columns to display for this list report
	 * (implemented via a different mechanism)
	 */
	function columns()
	{
		echo "This is not implemented";
	}

    /**
	 * Show a help page
	 * (implemented via a different mechanism)
	 */
	function help_page()
	{
		echo "This is not implemented";
	}

}

