<?php
// --------------------------------------------------------------------
// 'operations' style stored procedure functions
// --------------------------------------------------------------------

class Operation {

	private $config_source = '';

	// --------------------------------------------------------------------
	function __construct()
	{
	}
	
	// --------------------------------------------------------------------
	function init($config_name, $config_source)
	{
		$this->config_source = $config_source;
	}

	// --------------------------------------------------------------------
	// calls given stored procedure for this page family using calling parameters
	// derived from the sproc args definition for the stored procedure in config db
	// (and looks for a 'command' field in POST which is set to sproc arg 'mode')
	// returns a response object containing return value and message from sproc
	function internal_operation($sproc_name)
	{
		$CI = &get_instance();
		$config_name = $sproc_name;
		$response = new stdClass();

		try {
			// init sproc model
			$ok = $CI->cu->load_mod('s_model', 'sproc_model', $config_name, $this->config_source);
			if(!$ok) {
				throw new exception($CI->sproc_model->get_error_text());
			}
			
			// get sproc fields and use them to make validation field definitions
			$fields = $CI->sproc_model->get_sproc_fields();
			$rules = array();
			foreach($fields as $field) {
				$rule = array();
				$rule['field'] = $field;
				$rule['label'] =  $field;
				$rule['rules'] =  'trim'; // someday: rule to require presence of arg?
				$rules[] = $rule;
			}
		
			// make validation object and use it to 
			// get field values from POST and validate them
			$CI->load->helper('form');
			$CI->load->library('form_validation');
			$CI->form_validation->set_error_delimiters('', '');
			$CI->form_validation->set_rules($rules);
			$valid_fields = $CI->form_validation->run();
						
			// get field values from validation objec into an object
			// that will be used for calling stored procedure
			// and also putting values back into entry form HTML 
			$CI->load->helper('user');
			$calling_params = new stdClass();
			foreach($fields as $field) {
				$calling_params->$field = $CI->form_validation->set_value($field);
			}
			$calling_params->mode = ($CI->input->post('mode')) ? $CI->input->post('mode') : $CI->input->post('command');
			$calling_params->callingUser = get_user();
			$calling_params->message = '';
			
			// call sproc
			$success = $CI->sproc_model->execute_sproc($calling_params);
			if(!$success) {
				throw new exception($CI->sproc_model->get_error_text());
			}
	
			$response->result = $CI->sproc_model->get_parameters()->retval;
			$response->message = $CI->sproc_model->get_parameters()->message;			
		} catch (Exception $e) {
			$response->result = -1;
			$response->message = $e->getMessage();			
		}
		return $response;
	}

	// --------------------------------------------------------------------
	// get params that sproc was called with, including changes passed back from sproc
	function get_params()
	{
		$CI = &get_instance();
		return $CI->sproc_model->get_parameters();
	}
	
}
