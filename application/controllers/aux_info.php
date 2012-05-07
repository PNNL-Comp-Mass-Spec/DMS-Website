<?php

class aux_info extends CI_Controller {

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
///--
		$this->load->helper(array('dms_search', 'cookie', 'user', 'dms_logging'));

		session_start();
		$this->load->model('dms_preferences', 'preferences');
		$this->load->model('dms_chooser', 'choosers');

		$this->help_page_link = $this->config->item('pwiki');
		$this->help_page_link .= $this->config->item('wikiHelpLinkPrefix');

		$this->color_code = $this->config->item('version_color_code');
///--
		$this->my_tag = "aux_info";	
		$this->my_model = "M_aux_info";	
		$this->my_title = "Aux Info";
		$this->my_list_action = "aux_info/report";
		$this->my_export_action = "aux_info/export";
		
		$this->load->helper(array('url', 'string', 'form'));
		$this->load->model($this->my_model, 'model', TRUE);

		$this->load->library('aux_info_support');
	}
	
	// --------------------------------------------------------------------
	function _set_aux_info_names($target, $id='')
	{
		$this->aux_info_support->item_entry_url = site_url().'aux_info/item_values/'.$target.'/';
		$this->aux_info_support->copy_info_url =  site_url().'aux_info_copy/update/';
		$this->aux_info_support->update_info_url = site_url()."aux_info/update";
		$this->aux_info_support->show_url = site_url()."aux_info/show/".$target."/".$id;
	}

	// --------------------------------------------------------------------
	// returns HTML to display current values for aux info items for 
	// given target and entity given by id
	function show($target, $id)
	{
		$this->load->helper('menu');

		// nav_bar setup
		$this->load->model('dms_menu', 'menu', TRUE);
		$data['nav_bar_menu_items']= get_nav_bar_menu_items('Aux_Info');

		$this->load->helper('detail_report_helper');
		try {
			$this->model->check_connection();

			$result = $this->model->get_aux_info_item_current_values($target, $id);
			if(count($result) == 0) {
				$str = "No aux info available";
			} else {
				$str = make_detail_report_aux_info_section($result);
			}
			echo $str;
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	// --------------------------------------------------------------------
	// presents the aux info entry page
	function entry($target, $id, $name)
	{
		$this->load->helper('menu');
		$this->_set_aux_info_names($target, $id);		
		$data['ais'] = $this->aux_info_support;

		// nav_bar setup
		$this->load->model('dms_menu', 'menu', TRUE);
		$data['nav_bar_menu_items']= get_nav_bar_menu_items('Aux_Info');

		// labelling information for view
		$data['title'] = "Aux Info Entry";
		$data['heading'] = "Aux Info Entry";
		$data['target'] = $target;
		$data['id'] = $id;
		$data['name'] = $name;

		try {
			$this->model->check_connection();

			$data['categories']= $this->model->get_aux_info_categories($target);			
			$data['aux_info_def'] = $this->model->get_aux_info_def($target);

			// load up data array and call view template
			$this->load->vars($data);
			$this->load->view('special/aux_info_entry');
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	// --------------------------------------------------------------------
	// make entry form for subcategory items via AJAX ()called by loadItemEntryForm)
	function item_values($target)
	{
		$category = $this->input->post('category');
		$subcategory = $this->input->post('subcategory');
		$id = $this->input->post('id');
		$this->_set_aux_info_names($target, $id);		
		try {
			$this->model->check_connection();
//			$result = $this->model->get_aux_info_item_values($target, $category, $subcategory, $id);
			list($ai_items, $ai_choices) = $this->model->get_aux_info($target, $category, $subcategory, $id);
			echo $this->aux_info_support->make_item_entry_form($ai_items, $ai_choices);
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}
	}	

	// --------------------------------------------------------------------		
	// --------------------------------------------------------------------
	// update database (from AJAX call)
	function update()
	{
		$fields = $this->model->get_field_validation_fields();

		// get expected field values from POST
		$obj = new stdClass();
		foreach(array_keys($fields) as $name) {
			$obj->$name = isset($_POST[$name])?$_POST[$name]:'';
		}

		// collect the item names and item values into delimited lists
		$fn = ''; 
		foreach($obj->FieldNamesEx as $nf) {
			$fn .= $nf.'!';
		}
		$obj->FieldNamesEx = $fn;
		$fv = ''; 
		foreach($obj->FieldValuesEx as $vf) {
			$fv .= $vf.'!';
		}
		$obj->FieldValuesEx = $fv;

		$message = "";
		$result = $this->model->add_or_update($obj, "add", $message);
		if($result != 0) {
			echo "($result):$message";
		} else {
			echo "Update was successful";
		}
	}
}
?>