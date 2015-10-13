<?php
// --------------------------------------------------------------------
// misc functions for controllers that use page libraries
// --------------------------------------------------------------------

class Controller_utility {
	
	// --------------------------------------------------------------------
	function message_box($heading, $message, $title = '')
	{
		$data['title'] = ($title)?$title:$heading;
		$data['heading'] = $heading;
		$data['message'] = $message;
		$CI = &get_instance();
		$CI->load->view('message_box', $data);
	}
	
	// --------------------------------------------------------------------
	// load named library and initialize it with given config info
	function load_lib($lib_name, $config_name, $config_source, $options = FALSE)
	{
		$CI = &get_instance();
		if(property_exists($CI, $lib_name)) return TRUE;
		$CI->load->library($lib_name);
		if($options === FALSE) {
			return $CI->$lib_name->init($config_name, $config_source);
		} else {
			return $CI->$lib_name->init($config_name, $config_source, $options);
		}
	}

	// --------------------------------------------------------------------
	// load named model (with given local name) and initialize it with given config info
	function load_mod($model_name, $local_name, $config_name, $config_source)
	{
		$CI = &get_instance();
		if(property_exists($CI, $local_name)) return TRUE;
		$CI->load->model($model_name, $local_name);
		return $CI->$local_name->init($config_name, $config_source);
	}

	// --------------------------------------------------------------------
	// Verify (all):
	// - action is allowed for the page family
	// - user has at least basic access to website
	// - user has necessary permission if action is a restricted one
	// Present message box if access check fails and $output_message is true
	function check_access($action, $output_message = TRUE)
	{
		$CI = &get_instance();
		$CI->load->helper('user');
		$user = get_user();

		$this->load_mod('g_model', 'gen_model', 'na', $CI->my_tag);
		
		if($CI->gen_model->error_text) {
			if($output_message) {
				$this->message_box('Error', $CI->gen_model->error_text);
			}
			return FALSE;			
		}
		
		$result = $CI->gen_model->check_permission($user, $action, $CI->my_tag);

		if($result === TRUE) {
			return TRUE;
		} else {
			if($output_message) {
				$this->message_box('Access Denied', $result);
			}
			return FALSE;
		}
	}
}
?>