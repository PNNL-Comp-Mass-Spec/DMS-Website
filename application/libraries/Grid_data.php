<?php
// --------------------------------------------------------------------
// supports editing grid data
// --------------------------------------------------------------------

class Grid_data {

	private $config_source = '';
	private $config_name = '';

	// --------------------------------------------------------------------
	function __construct()
	{
	}
	
	// --------------------------------------------------------------------
	function init($config_name, $config_source)
	{
		$this->config_source = $config_source;
		$this->config_name = $config_name;
	}

	// --------------------------------------------------------------------
	function get_query_data($sql, $paramArray) {
		$CI = &get_instance();
		$response = new stdClass();
		try {
			$CI->load->database();
			$result = $CI->db->query($sql);
			if(!$result) {
				throw new exception('??');
			}
			$columns = array();
			foreach($result->field_data() as $field) {
				$columns[] = $field->name;
			}
			$response->result = 'ok';
			$response->message = '';
			$response->columns = $columns;
			$response->rows = $result->result_array();
		} catch (Exception $e) {
			$response->result = 'error';
			$response->message = $e->getMessage();			
		}
		echo json_encode($response);
	}

	// --------------------------------------------------------------------
	// get data from sproc
	function get_sproc_data($paramArray, $config_name = '')
	{
		$CI = &get_instance();
		
		if(!$config_name) {
			$config_name = $this->config_name;
		}

		$CI->load->helper(array('user','url'));
		$response = new stdClass();
		try {
			// init sproc model
			$ok = $CI->cu->load_mod('s_model', 'sproc_model', $config_name, $this->config_source);
			if(!$ok) {
				throw new exception($CI->sproc_model->get_error_text());
			}
			
			$fields = $CI->sproc_model->get_sproc_fields();
			$paramObj = $this->get_input_values($fields, $paramArray);
			$calling_params = $CI->sproc_model->get_calling_args($paramObj);

			$success = $CI->sproc_model->execute_sproc($calling_params);
			if(!$success) {
				throw new exception($CI->sproc_model->get_error_text());
			}
	
			$response->result = 'ok';
			$response->message = $CI->sproc_model->get_parameters()->message;	
			
			$response->columns = $CI->sproc_model->get_col_names();
			$response->rows = $CI->sproc_model->get_rows();
		} catch (Exception $e) {
			$response->result = 'error';
			$response->message = $e->getMessage();			
		}
		return $response;
	}

	// --------------------------------------------------------------------
	private 
	function get_input_values($fields, $paramArray) {
		if($paramArray === false) {
			$paramArray = array();
		}
		$paramObj = new stdClass();
		foreach($fields as $field) {
			$paramObj->$field = (array_key_exists ($field, $paramArray)) ? $paramArray[$field] : '';
		}
		return $paramObj;
	}
	
	// --------------------------------------------------------------------
	private
	function make_col_specs($colNames) 
	{
		$colSpec = array();
		foreach($colNames as $colName) {
			$spec = new stdClass();
			$colSpec[] = $spec;
		}
		return colSpec;
	}

}
