<?php

class Paging_filter {
	const storage_name_root = "lr_paging_filter_";
	
	private $config_name = '';
	private $config_source = '';
	
	private $field_names = array('qf_first_row', 'qf_rows_per_page');
	private $cur_filter_values = NULL;
	
	// --------------------------------------------------------------------
	function __construct()
	{
	}

	/**
	 * Get current secondary filter values either from POST
	 * or from cache storage (session)
	 * @param type $config_name
	 * @param type $config_source
	 */
	function init($config_name, $config_source)
	{
		$CI =& get_instance();
		$CI->load->helper('cache');

		$this->config_name = $config_name;
		$this->config_source = $config_source;
		$storage_name = self::storage_name_root.$this->config_name.'_'.$this->config_source;
			
		$this->clear_filter();
		
		// try to get current values of filters from POST
		$state = $this->get_current_filter_values_from_post($this->field_names);
		if($state) {
			$this->cur_filter_values = $state;
			$state['qf_first_row'] = 1; // don't remember first row between visits
			save_to_cache($storage_name, $state);
		}
		else {
			// try to get current values of filters from cache
			$state = get_from_cache($storage_name);
			if($state) {
				$this->cur_filter_values = $state;
			} else {
				// user global defaults (if any)
				$CI->load->model('dms_preferences', 'preferences');
				$x = $CI->preferences->get_preference('list_report_rows');
				if($x) { 
					$this->cur_filter_values['qf_rows_per_page'] = $x;
					$state = $this->cur_filter_values;
					save_to_cache($storage_name, $state);
				}
			}
		}
	}

	/**
	 * Get current values for secondary filter if present in POST
	 * otherwise return FALSE
	 * @param type $field_names
	 * @return boolean
	 */
	private
	function get_current_filter_values_from_post($field_names)
	{
		$values = array();
		
		if(!empty($_POST)){
			foreach($field_names as $id) {
				$filterVal = filter_input(INPUT_POST, $id, FILTER_SANITIZE_SPECIAL_CHARS);
				if(!empty($filterVal)) { 
					$values[$id] = trim($filterVal);
				}
			}
			return $values;
		} else {
			return FALSE;
		}
	}

	/**
	 * Reset (clear) the filter
	 */
	private
	function clear_filter()
	{
		$this->cur_filter_values = array();
		$this->cur_filter_values['qf_first_row'] = 1;
		$this->cur_filter_values['qf_rows_per_page'] = 10;
	}

	/**
     * Get current filter values
     * @return type
     */
	function get_current_filter_values()
	{
		return $this->cur_filter_values;
	}

	/**
     * Get cached values
     * @return type
     */
	function get_cached_value()
	{
		return get_from_cache($this->storage_name);
	}

	/**
     * Clear cached data
     */
	function clear_cached_state()
	{
		$CI =& get_instance();
		$CI->load->helper('cache');
		if (property_exists($this, "storage_name")) {
			clear_cache($this->storage_name);
		}
	}
}
