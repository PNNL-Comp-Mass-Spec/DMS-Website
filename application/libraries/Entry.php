<?php
// --------------------------------------------------------------------
// entry page section
// --------------------------------------------------------------------

class Entry {

	protected $config_source = '';

	protected $tag = '';
	protected $title = '';
	
	// --------------------------------------------------------------------
	function __construct()
	{
	}

	// --------------------------------------------------------------------
	function init($config_name, $config_source)
	{
		$this->config_source = $config_source;

		$CI = &get_instance();
		$this->tag = $CI->my_tag;
		$this->title = $CI->my_title;
	}	

	// --------------------------------------------------------------------
	// make an entry page to create or update (according to $page_type)
	// a record in the database (the entry form is subsequently submitted 
	// via AJAX call to function submit_entry_form)
	function create_entry_page($page_type)
	{
		$CI = &get_instance();
		$CI->load->helper(array('entry_page'));

		// general specifications for page family
		$CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);
		
		// make entry form object using form definitions from model
		$CI->cu->load_mod('e_model', 'form_model', 'na', $this->config_source);
		$form_def = $CI->form_model->get_form_def(array('fields', 'specs', 'entry_commands', 'enable_spec'));
		$form_def->field_enable = $this->get_field_enable($form_def->enable_spec);
		//
		$CI->cu->load_lib('entry_form', $form_def->specs, $this->config_source);
		
		// get initial field values and merge them with form object
		$segs = array_slice($CI->uri->segment_array(), 2); // remove controller and function segments
		$initial_field_values = get_initial_values_for_entry_fields($segs, $this->config_source, $form_def->fields);
		foreach($initial_field_values as $field => $value) {
			$CI->entry_form->set_field_value($field, $value);
		}
		
		// handle special field options for entry form object
		$mode = $CI->entry_form->get_mode_from_page_type($page_type);
		$this->handle_special_field_options($form_def, $mode);
		
		// build page display components and load page
		$data['tag'] = $this->tag;
		$data['title'] = $CI->gen_model->get_page_label($this->title, $page_type);
		$data['form'] = $CI->entry_form->build_display();
		$data['entry_cmds'] = $this->handle_cmd_btns($CI, $form_def->entry_commands, $page_type);
		$data['entry_submission_cmds'] = $CI->gen_model->get_param('entry_submission_cmds');

		$CI->load->helper(array('menu', 'link_util'));
		$data['nav_bar_menu_items']= set_up_nav_bar('Entry_Pages');
		$CI->load->vars($data);	
		$CI->load->view('main/entry_form');		
	}

	// --------------------------------------------------------------------
	protected
	function handle_cmd_btns($me, $commands, $page_type)
	{
		$btns = '';
		$suppress_btns = $me->gen_model->get_param('cmd_buttons');
		if(!$suppress_btns) {
			$btns = $me->entry_form->make_entry_commands($commands, $page_type);
		}		
		return $btns;
	}

	// --------------------------------------------------------------------
	// handle special field options for entry form object
	protected
	function handle_special_field_options($form_def, $mode)
	{
		$CI = &get_instance();

		$CI->entry_form->set_field_enable($form_def->field_enable);		

		if($mode) {
			$CI->entry_form->adjust_field_visibility($mode);
		}

		$userPermissions = $CI->auth->get_user_permissions(get_user());
		$CI->entry_form->adjust_field_permissions($userPermissions);
	}

	// --------------------------------------------------------------------
	// create or update entry in database from entry page form fields in POST:
	// use entry form definition from config db
	// validate entry form information, submit to database if valid,
	// and return HTML containing entry form with updated values
	// and sucess/failure messages
	// AJAX
	function submit_entry_form()
	{
		$CI = &get_instance();
		$CI->load->helper(array('entry_page'));
		
		$CI->cu->load_mod('e_model', 'form_model', 'na', $this->config_source);
		$form_def= $CI->form_model->get_form_def(array('fields', 'specs', 'rules', 'enable_spec'));
		$form_def->field_enable = $this->get_field_enable($form_def->enable_spec);
		
		$valid_fields = $this->get_input_field_values($form_def->rules);

		// get field values from validation object
		$input_params = new stdClass();
		foreach($form_def->fields as $field) {
			$input_params->$field = $CI->form_validation->set_value($field);
		}
		try {
			if (!$valid_fields) {
				throw new exception('There were validation errors');
			}
			
			// $msg is an output parameter of call_stored_procedure
			$msg = '';
			$this->call_stored_procedure($input_params, $form_def, $msg);
			
			// everything worked - compose tidings of joy
			$ps_links = $this->get_post_submission_link($input_params);
			$message = 'Operation was successful. ';
			$outcome = $this->outcome_msg($message . $msg, 'normal');
			$supplement = $this->supplement_msg($message . $ps_links, 'normal');
		} catch (Exception $e) {
			// something broke - compose expressions of regret
			$message = $e->getMessage();
			$outcome = $this->outcome_msg($message, 'failure');
			$supplement = $this->supplement_msg($message, 'error');
		}

		// get entry form object and use to to build and return HTML for form
		$CI->cu->load_lib('entry_form', $form_def->specs, $this->config_source);
		$data['form'] = $this->make_entry_form_HTML($input_params, $form_def);
		echo $outcome;
		echo $data['form'];
		echo $supplement;
	}

	// --------------------------------------------------------------------
	private
	function outcome_msg($message, $option)
	{
		return entry_outcome_message($message, $option, 'main_outcome_msg');
	}
	private
	function supplement_msg($message, $option)
	{
		return entry_outcome_message($message, $option, 'supplement_outcome_msg');
	}
	// --------------------------------------------------------------------
	// get entry form builder object and use it to make HTML
	protected
	function make_entry_form_HTML($input_params, $form_def)
	{
		$CI = &get_instance();

		// handle special field options for entry form object
		$mode = (property_exists($input_params, 'mode'))?$input_params->mode:'';
		$this->handle_special_field_options($form_def, $mode);
		
		// update entry form object with field values 
		// and any field validation errors
		foreach($form_def->fields as $field) {
			$CI->entry_form->set_field_value($field, $input_params->$field);
			$CI->entry_form->set_field_error($field, form_error($field));
		}
		// build HTML and return it
		return $CI->entry_form->build_display();
	}
	
	// --------------------------------------------------------------------
	// make post-submission links to list report and detail report
	protected
	function get_post_submission_link($input_params)
	{
		$CI = &get_instance();
		$ps_link_specs = $CI->gen_model->get_post_submission_link_specs();
		$actions = $CI->gen_model->get_actions();
		return make_post_submission_links($CI->my_tag, $ps_link_specs, $input_params, $actions);		
	}
	
	// --------------------------------------------------------------------
	protected
	function call_stored_procedure($input_params, $form_def, out &$msg)
	{
		$CI = &get_instance();
		// call stored procedure		
		$ok = $CI->cu->load_mod('s_model', 'sproc_model', 'entry_sproc', $this->config_source);
		if(!$ok) { 
			throw new exception($CI->sproc_model->get_error_text());
		}

		$calling_params = $this->make_calling_param_object($input_params, $form_def->field_enable);
		$success = $CI->sproc_model->execute_sproc($calling_params);
		if(!$success) {
			throw new exception($CI->sproc_model->get_error_text());
		}
		
		$msg = $CI->sproc_model->get_parameters()->message;
		$this->update_input_params_from_stored_procedure_args($input_params);
	}
	
	// --------------------------------------------------------------------
	// copy values from params that were bound to stored procedure arguments
	// back to input param object
	protected
	function update_input_params_from_stored_procedure_args($input_params)
	{
		$CI = &get_instance();
		$bound_params = $CI->sproc_model->get_parameters();
		foreach($CI->sproc_model->get_sproc_args() as $arg) {
			if($arg['dir'] == 'output') {
				$fn = ($arg['field'] == '<local>')?$arg['name']:$arg['field'];
				if(isset($input_params->$fn) && $bound_params->$fn != '[no change]') {
					$input_params->$fn = $bound_params->$fn;
				}	
			}
		}
		if(isset($bound_params->mode)) {
			$input_params->mode = $bound_params->mode;
		}
	}
	
	// --------------------------------------------------------------------
	// make validation object and use it to 
	// get field values from POST and validate them
	protected
	function get_input_field_values($rules)
	{
		$CI = &get_instance();
		$CI->load->helper('form');
		$CI->load->library('form_validation');
		$CI->form_validation->set_error_delimiters('<span class="bad_clr">', '</span>');
		$CI->form_validation->set_rules($rules);
		return $CI->form_validation->run();
	}
	
	// --------------------------------------------------------------------
	// copy (shallow) input param object to proxy object 
	// for actually supplying values to call stored procedure 
	protected
	function make_calling_param_object($input_params, $field_enable)
	{
		$CI = &get_instance();
		$calling_params = clone $input_params;
		$calling_params->mode = $CI->input->post('entry_cmd_mode');
		$calling_params->callingUser = get_user();
		
		// adjust calling parameters for any disabled fields
		foreach($field_enable as $field => $status) {
			if($status == 'disabled') {
				$calling_params->$field = '[no change]';
			}
		}
		return $calling_params;
	}

	// --------------------------------------------------------------------
	// update input field list with enable/disable status from POST
	// input field list designates whether or not each field has an
	// enable/disable checkbox field and the ones that do have will be
	// updated from the checked state of the associated checkboxes from POST
	protected
	function get_field_enable($field_enable)
	{
		$suffix = '_ckbx_enable';
		foreach($field_enable as $f_name => $mode) {
			$enable_field_name = $f_name . $suffix;
			if($mode != 'none') {
				if(array_key_exists($enable_field_name, $_POST)) {
					$field_enable[$f_name] = 'enabled';
				} else {
					$field_enable[$f_name] = 'disabled';
				}
			}
		}
		return $field_enable;
	}
	
}
