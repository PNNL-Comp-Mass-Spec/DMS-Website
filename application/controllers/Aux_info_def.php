<?php
require("Base_controller.php");

class Aux_info_def extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "aux_info_def";
		$this->my_title = "Aux Info Definition";
	}

	// --------------------------------------------------------------------
	function test($config_name = 'aux_info_targets',  $id = '')
	{
		$this->load->library('controller_utility', '', 'cu');

		$this->cu->load_mod('q_model', 'data_model', $config_name, $this->my_tag);
		$filter_specs = $this->data_model->get_primary_filter_specs();
		foreach($filter_specs as $spec) {
			$this->data_model->add_predicate_item('AND', $spec['col'], $spec['cmp'], $id);
		}
		$rows = $this->data_model->get_rows('filtered_and_sorted')->result_array();

		$options = array();
		foreach($rows as $row) {
			$options[$row['ID']] = $row['Name'];
		}
		if(empty($options)) {
			echo "(none)";
		} else {
			$this->load->helper(array('form'));
			$fn = "getChildren(\"$config_name\")";
			$sz = count($options);
			echo form_multiselect('bob', $options, '', "size='$sz' id='$config_name' onclick='$fn'");
			if($id) {
				echo "<a href='javascript:void(0)' onclick='addNewMember(\"$config_name\", \"$id\")' >add new member to $config_name </a>";
			}
		}
	}


	// --------------------------------------------------------------------
	function def()
	{
		$data['title'] = 'Aux Info Definition';
		$this->load->vars($data);
		$this->load->view('special/aux_info_def');
	}
}
?>