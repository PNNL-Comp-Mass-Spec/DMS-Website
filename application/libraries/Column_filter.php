<?php

class Column_filter {
	const storage_name_root = "lr_column_filter_";
	
	private $config_name = '';
	private $config_source = '';
	private $storage_name = '';
	
	private $cur_filter_values = array();
	
	// --------------------------------------------------------------------
	function __construct()
	{
	}

	// --------------------------------------------------------------------
	// get current secondary filter values either from POST
	// or from cache storage (session)
	function init($config_name, $config_source)
	{
		$CI =& get_instance();
		$CI->load->helper('cache');

		$this->config_name = $config_name;
		$this->config_source = $config_source;
		$this->storage_name = self::storage_name_root.$this->config_name.'_'.$this->config_source;
			
		$this->clear_filter();
		
		// try to get current values of filters from POST
		$state = $this->get_current_filter_values_from_post();		
		if($state !== FALSE) {
			$this->cur_filter_values = $state;
			save_to_cache($this->storage_name, $state);
		}
		else {
			// try to get current values of filters from cache
			$state = get_from_cache($this->storage_name);
			if($state) {
				$this->cur_filter_values = $state;
			} else {
				// user preference defaults (if any)
			}
		}
	}

	// --------------------------------------------------------------------
	// get current values for filter if present in POST
	// otherwise return FALSE
	private
	function get_current_filter_values_from_post()
	{
		// we need to be able to tell the difference between an empty post
		// (signifying a new page visit) and a post that happens to contain
		// an empty list of columns.  the presence of "cf_column_selection_marker"
		// does that
		$selected_items = FALSE;
		if(array_key_exists('cf_column_selection_marker', $_POST)) {
			if(array_key_exists('cf_column_selection', $_POST)) {
				$selected_items = $_POST['cf_column_selection'];
			} else {
				$selected_items = array();
			}
		}
		return $selected_items;
	}

	// --------------------------------------------------------------------
	// set filter so that it will not be used to filter results
	private
	function clear_filter()
	{
		$this->cur_filter_values =  array();
	}

	// --------------------------------------------------------------------
	function get_current_filter_values()
	{
		return $this->cur_filter_values;
	}

	// --------------------------------------------------------------------
	function get_storage_name()
	{
		return $this->storage_name;
	}

	// --------------------------------------------------------------------
	function get_cached_value()
	{
		return get_from_cache($this->storage_name);
	}

	// --------------------------------------------------------------------
	function clear_cached_state()
	{
		$CI =& get_instance();
		$CI->load->helper('cache');
		clear_cache($this->storage_name);
	}
}
