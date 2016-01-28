<?php
// --------------------------------------------------------------------
// 
// --------------------------------------------------------------------

class Saved_settings {

	
	// --------------------------------------------------------------------
	function __construct()
	{
	}
	
	// --------------------------------------------------------------------
	// clears cached session variables
	// (intended for calling via AJAX)
	function defaults($page_type, $config_source) //'Param_Pages''list_report_sproc'   'list_report'
	{
		$CI = &get_instance();
		session_start();

		if($page_type == 'List_Reports') {
			$config_name = 'list_report';
		} else
		if($page_type == 'Param_Pages') {
			$config_name = 'list_report_sproc';
		} else {
			echo "Unrecognized page type '$page_type'";
			return;
		}

		if($page_type == 'List_Reports') {
			// it all starts with a model
			$CI->cu->load_mod('q_model', 'data_model', $config_name, $config_source);
			$primary_filter_specs = $CI->data_model->get_primary_filter_specs();
			$CI->data_model->clear_cached_state();
			
			// primary filter
			$CI->load->library('primary_filter');
			$CI->primary_filter->init($config_name, $config_source, $primary_filter_specs);
			$CI->primary_filter->clear_cached_state();
			
			// secondary filter
			$CI->cu->load_lib('secondary_filter', $config_name, $config_source);
			$CI->secondary_filter->clear_cached_state();
		} else
		if($page_type == 'Param_Pages') {
			$CI->cu->load_mod('s_model', 'sproc_model',$config_name, $config_source);
			$CI->sproc_model->clear_cached_state();
			
		}

		// paging filter		
		$CI->cu->load_lib('paging_filter', $config_name, $config_source);
		$CI->paging_filter->clear_cached_state();

		// sorting filter
		$CI->cu->load_lib('sorting_filter', $config_name, $config_source);
		$CI->sorting_filter->clear_cached_state();
		
		// column filter
		$CI->cu->load_lib('column_filter', $config_name, $config_source);
		$col_filter = $CI->column_filter->clear_cached_state();
		
		echo "Saved preferences were cleared";
	}
	
}
?>