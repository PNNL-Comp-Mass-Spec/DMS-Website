<?php
// --------------------------------------------------------------------
// list report page section
// --------------------------------------------------------------------

class Detail_report {

	private $config_source = '';
	private $config_name = '';

	private $tag = '';
	private $title = '';
	
	// --------------------------------------------------------------------
	function __construct()
	{
	}
	
	// --------------------------------------------------------------------
	function init($config_name, $config_source)
	{
		$this->config_name = $config_name;
		$this->config_source = $config_source;

		$CI = &get_instance();
		$this->tag = $CI->my_tag;
		$this->title = $CI->my_title;
	}

	// --------------------------------------------------------------------
	// make a page to show a detailed report for the single record identified by the 
	// the user-supplied id
	function detail_report($id)
	{
		$CI = &get_instance();
		
		$CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);
		$data['title'] = $CI->gen_model->get_page_label($this->title, 'show');

		$data['tag'] = $this->tag;
		$data['id'] = $id;
		$data['commands'] = $CI->gen_model->get_detail_report_commands();
		$dcmdp = $CI->gen_model->get_detail_report_cmds();
		$dcmds = array();
		foreach(explode(",", $dcmdp) as $dcmd) {
			$c = trim($dcmd);
			if($c) {
				$dcmds[] = $c;
			}
		}
		$data['detail_report_cmds'] = $dcmds;
		$data['aux_info_target'] = $CI->gen_model-> get_detail_report_aux_info_target();
		
		$CI->load->helper(array('detail_report', 'menu', 'link_util'));
		$data['nav_bar_menu_items']= set_up_nav_bar('Detail_Reports');
		$CI->load->vars($data);	
		$CI->load->view('main/detail_report');
	}

	// --------------------------------------------------------------------
	// get detail report data for specified entity
	// AJAX
	function detail_report_data($id, $show_entry_links = TRUE)
	{
		$CI = &get_instance();

		try {
			// get data
			$CI->cu->load_mod('q_model', 'detail_model', $this->config_name, $this->config_source);
			$result_row = $CI->detail_model->get_item($id);
			if(empty($result_row)) throw new exception("Details for entity '$id' could not be found");

			// hotlinks
			$CI->cu->load_mod('r_model', 'link_model', 'na', $this->config_source);
	
			// render with old detail report helper
			$data['my_tag'] = $this->tag;
			$data['id'] = $id;
			$data["fields"] = $result_row;
			$data["hotlinks"] = $CI->link_model->get_detail_report_hotlinks();
			$data['show_entry_links'] = $show_entry_links;
	
			$CI->load->helper(array('string', 'detail_report_helper'));
			$CI->load->vars($data);
			$CI->load->view('main/detail_report_data');
		} catch (Exception $e) {
			echo "<div class='EPag_message' >" . $e->getMessage() . "</div>";
		}
	}

	// --------------------------------------------------------------------
	// returns HTML displaying the list report data rows
	// for inclusion in list report page
	// AJAX
	function detail_sql($id)
	{
		$CI = &get_instance();
		session_start();
		
		$CI->cu->load_mod('q_model', 'detail_model', $this->config_name, $this->config_source);
		echo $CI->detail_model->get_item_sql($id);
	}
	
	// --------------------------------------------------------------------
	// get aux info controls associated with specified entity
	// AJAX
	function detail_report_aux_info_controls($id)
	{
		$CI = &get_instance();

		$CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);
		$aux_info_target = $CI->gen_model-> get_detail_report_aux_info_target();
		
		// aux_info always needs numeric ID, and sometimes ID for detail report is string 
		// this is a bit of a hack to always get the number
		$CI->cu->load_mod('q_model', 'detail_model', $this->config_name, $this->config_source);
		$result_row = $CI->detail_model->get_item($id);	
		$aux_info_id = (array_key_exists('ID', $result_row))?$result_row['ID']:$id;

		$CI->load->helper(array('string', 'detail_report_helper'));
		echo make_detail_report_aux_info_controls($aux_info_target, $aux_info_id, $id);
	}

	
	// --------------------------------------------------------------------
	// export detailed report for the single record identified by the 
	// the user-supplied id
	function export_detail($id, $format)
	{
		$CI = &get_instance();
		session_start();

		// get entity data
		$CI->cu->load_mod('q_model', 'detail_model', $this->config_name, $this->config_source);
		$entity_info = $CI->detail_model->get_item($id);	

		$aux_info_id = (array_key_exists('ID', $entity_info))?$entity_info['ID']:FALSE;
		$aux_info = array();
		if($aux_info_id) {
			$aux_info = $this->get_aux_info($aux_info_id);
		}

		$CI->load->helper(array('string', 'detail_report_helper', 'export'));
		switch($format) {
			case 'excel':
				export_detail_to_excel($entity_info, $aux_info, $this->tag."_detail");
				break;
			case 'tsv':
				export_detail_to_tab_delimited_text($entity_info, $aux_info, $this->tag."_detail");
				break;
			case 'json':
				header("Content-type: application/json");
				echo json_encode($entity_info);
				break;
			case 'test':
				print_r($entity_info); echo '<hr>';
				echo "$aux_info_id <hr>";
				print_r($aux_info); echo '<hr>';
				break;
		}
	}

	// --------------------------------------------------------------------
	private
	function get_aux_info($aux_info_id)
	{
		$CI = &get_instance();
		$CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);
		$aux_info_target = $CI->gen_model-> get_detail_report_aux_info_target();

		// get aux into data
		$CI->cu->load_mod('q_model', 'aux_info_model', '', '');
		$CI->aux_info_model->set_columns('Target, Target_ID, Category, Subcategory, Item, Value, SC, SS, SI');
		$CI->aux_info_model->set_table('V_AuxInfo_Value');
		$CI->aux_info_model->add_predicate_item('AND', 'Target', 'Equals', $aux_info_target);
		$CI->aux_info_model->add_predicate_item('AND', 'Target_ID', 'Equals', $aux_info_id);
		$CI->aux_info_model->add_sorting_item('SC');
		$CI->aux_info_model->add_sorting_item('SS');
		$CI->aux_info_model->add_sorting_item('SI');
		return $CI->aux_info_model->get_rows('filtered_and_sorted')->result_array();
	}

	// --------------------------------------------------------------------
	private
	function get_entry_aux_info($id)
	{
		$CI = &get_instance();

		$aux_info = array();
		$CI->cu->load_mod('g_model', 'gen_model', 'na', $this->config_source);
		$aux_info_target = $CI->gen_model-> get_detail_report_aux_info_target();
		if($aux_info_target) {
			// get data
			$CI->cu->load_mod('q_model', 'detail_model', 'detail_report', $this->config_source);
			$result_row = $CI->detail_model->get_item($id);
			// get aux info data
			$aux_info_id = (array_key_exists('ID', $result_row))?$result_row['ID']:$id;
			$aux_info = $this->get_aux_info($aux_info_id);
		}
		return $aux_info;
	}

	// --------------------------------------------------------------------
	// get the field information that would appear on the entry page for the given
	// entity (label -> value)
	private
	function get_entry_tracking_info($id)
	{
		$CI = &get_instance();
		
		// get definition of fields for entry page
		$CI->cu->load_mod('e_model', 'form_model', 'na', $this->config_source);
		$form_def = $CI->form_model->get_form_def(array('fields', 'specs', 'load_key'));

		$CI->cu->load_lib('entry_form', $form_def->specs, $this->config_source);
		
		// get get entry field values for this entity
		$CI->cu->load_mod('q_model', 'input_model', 'entry_page', $this->config_source);
		$field_values = $CI->input_model->get_item($id);

		// get entity key field
		$primary_key = $form_def->load_key;
		
		// get array of field labels associated with field values
		// make sure key field is first in list
		$entity_info[$form_def->specs[$primary_key]['label']] = $field_values[$primary_key];
		foreach($form_def->specs as $field => $spec) {
			if($field != $primary_key and $spec['type'] != "hidden" and $spec['type'] != "non-edit") {
				$entity_info[$spec['label']] = $field_values[$field];
			}
		}
		return $entity_info;
	}
	
	// --------------------------------------------------------------------
	// export spreadsheet template for the single record identified by the 
	// the user-supplied id
	function export_spreadsheet($id, $format)
	{
		$CI = &get_instance();
		session_start();

		$entity_info = $this->get_entry_tracking_info($id);
		$aux_info = $this->get_entry_aux_info($id);

		$CI->load->helper(array('export'));
		switch($format) {
			case 'data':
				export_spreadsheet($this->tag, $entity_info, $aux_info, $this->tag."_template");
				break;
			case 'blank':
				export_spreadsheet($this->tag, $entity_info, $aux_info, $this->tag."_template");
				break;
			case 'test':
				dump_spreadsheet($entity_info, $aux_info);
				break;
		}
	}
	
	// --------------------------------------------------------------------
	// display contents of given script as graph
	function dot($scriptName, $config_source )
	{
		$CI = &get_instance();
		$CI->load->helper(array('url', 'string', 'export'));
		$config_name = 'dot';

		$CI->cu->load_mod('q_model', 'detail_model', $config_name, $config_source);
		$result_row = $CI->detail_model->get_item($scriptName);
		$script = $result_row['Contents'];
		$description = $result_row['Description'];
		
		export_xml_to_dot($scriptName, $description, $script);
	}

}
?>