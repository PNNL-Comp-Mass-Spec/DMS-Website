<?php

// Include application/libraries/Wildcard_conversion.php
require_once('Wildcard_conversion.php');

class Secondary_filter {
	const storage_name_root = "lr_sec_filter_";
	
	private $config_name = '';
	private $config_source = "";
	private $storage_name = "";
	
	private $qf_field_names = array('qf_rel_sel', 'qf_col_sel', 'qf_comp_sel', 'qf_comp_val');
	private $cur_qf_vals = array();
	
	private $qf_num_filters = 4;


	// --------------------------------------------------------------------
	function __construct()
	{
	}

	/**
     * Get the number of active filters
     * @return type
     */
	function get_num_filters()
	{
		return $this->qf_num_filters;
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
				
		$this->clear_query_filter();
		
		// try to get current values of filters from POST
		$state = $this->get_current_filter_values_from_post();
		if($state) {
			$this->cur_qf_vals = $state;
			save_to_cache($this->storage_name, $state);
		} else {
			// try to get current values of filters from cache
			$state = get_from_cache($this->storage_name);
			if($state) {
				$this->cur_qf_vals = $state;
			}
		}
	}
	
	/**
     * Get current values for secondary filter if present in POST
	 * Otherwise return FALSE
     * @return boolean
     */
	private
	function get_current_filter_values_from_post()
	{
		$CI =& get_instance();

		if($CI->input->post('qf_rel_sel')){
			$filter_values = array();
			foreach($this->qf_field_names as $name) {
				$xar = $CI->input->post($name);
				for($i=0; $i<count($xar); $i++) {
					$filter_values[$i][$name] = trim($xar[$i]);
				}
			}
			return $filter_values;
		} else {
			return FALSE;
		}
	}
	
	/**
     * Clear the filter
     */
	function clear_query_filter()
	{
		for($i=0; $i<$this->qf_num_filters; $i++) {
			foreach($this->qf_field_names as $name) {
				$this->cur_qf_vals[$i][$name]  = "";			
			}
		}					
	}
	
	/**
     * Collect info for building secondary filter display.
	 * Combines information from given model with current state of this secondary filter
	 * into a structure suitable for display or other output to be generated
     * @param type $model
     * @param type $url
     * @return \stdClass
     */
	function collect_information_for_display($model, $url = "data/get_sql_comparison/")
	{
		// get array of column names from model
		// and make paired array suitable for selector option list
		// and get name of first column in case there is no current value for column name
		$cn = $model->get_col_names();
		$first_col = current($cn);
		$cols = array_combine($cn, $cn);

		$fx = array();
		$relSelOpts = $model->get_allowed_rel_values();
		for($i=0; $i<$this->qf_num_filters; $i++) {
			// get current values for each field of current filter row
			$a = new stdClass();
			$a->relSelOpts = $relSelOpts;
			if($i < count($this->cur_qf_vals)) {
				$a->curRel = $this->cur_qf_vals[$i]['qf_rel_sel'];
				$a->curCol = $this->cur_qf_vals[$i]['qf_col_sel'];
				$a->curComp = $this->cur_qf_vals[$i]['qf_comp_sel'];
				$a->curVal = $this->cur_qf_vals[$i]['qf_comp_val'];
			} else {
				$a->curRel = "";
				$a->curCol = "";
				$a->curComp = "";
				$a->curVal = "";
			}
			//
			// make comparison selector options list for current
			// value of column (default to first column if current value is empty)
			$col = ($a->curCol)?($a->curCol):$first_col;
			$data_type = $model->get_column_data_type($col);
			$a->cmpSelOpts = $model->get_allowed_comparisons_for_type($data_type);
			//
			// set up selection parameters for column field including javascript
			// to refresh comparision selection list when column name selector is changed by user
			$c_url = site_url() . $url;
			$colSelID = "qf_col_sel_$i";
			$a->js = "id='".$colSelID."' onChange='lambda.loadSqlComparisonSelector(\"qf_comp_sel_container_$i\", \"$c_url\", \"$colSelID\")'";
			$a->flds = $cols;
			$fx[$i] = $a;
		}
		return $fx;
	}

	/**
     * Get current filter values
     * @return type
     */
	function get_current_filter_values()
	{
		return $this->cur_qf_vals;
	}
	
	/**
     * Get the storage path
     * @return type
     */
	function get_storage_name()
	{
		return $this->storage_name;
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
     * For building up current values from a simple ordered list
	 * (usually URL seqments)
     * @param type $items
     * @return string
     */
	function get_filter_from_list($items)
	{
		// build filters from list items
		$filter_state = array();
		$numItems = count($items);
		$itemIdx = 0;
		$filterIdx = 0;
		while($itemIdx < $numItems ) {
			foreach($this->qf_field_names as $name) {
				if ($itemIdx == 3) {
					// Check for special wildcard text
					$valueToStore = convert_special_values($items[$itemIdx]);
				} else {
					$valueToStore = $items[$itemIdx];
				}				
				$filter_state[$filterIdx][$name] = $valueToStore;
				$itemIdx++;
			}
			$filterIdx++;
		}
		// pad out filters
		$numFilters = count($filter_state);
		for($j = $numFilters; $j < $this->qf_num_filters; $j++) {
			foreach($this->qf_field_names as $name) {
				$filter_state[$j][$name]  = "";			
			}			
		}
		return $filter_state;
	}	

	/**
     * Save current filter values to the cache
     * @param type $filter_state
     */
	function save_filter_values($filter_state)
	{		
		save_to_cache($this->storage_name, $filter_state);
	}

	/**
     * Clear cached data
     */
	function clear_cached_state()
	{
		$CI =& get_instance();
		$CI->load->helper('cache');
		clear_cache($this->storage_name);
	}
}
