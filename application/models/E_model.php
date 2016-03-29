<?php
// manages specifications for an entry form

// main class
class E_model extends CI_Model {
	
	private $config_name = '';
	private $config_source = '';
	private	$configDBFolder = "";
	
	// definitions of fields for entry form
	private $form_fields = array();

	// definitions of external sources for entry form
	private $external_sources = array();

	// definitions of entry page commands
	private $entry_commands = array();
		
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->configDBFolder = $this->config->item('model_config_path');
	}
	
	// --------------------------------------------------------------------
	function init($config_name, $config_source)
	{
		$this->error_text = '';
		try {
			$this->config_name = $config_name;
			$this->config_source = $config_source;
	
			$dbFileName = $config_source . '.db';

			$this->get_entry_form_definitions($config_name, $dbFileName);
			return TRUE;
		} catch (Exception $e) {
			$this->error_text = $e->getMessage();
			return FALSE;
		}
	}

	// --------------------------------------------------------------------
	// return an object with member fields representing the different parameter 
	// collections that are defined for the entry mode
	// the specific collections are selected by the input list
	// array('fields', 'rules', 'specs', 'load_key', 'enable_spec', 'entry_commands')
	function get_form_def($which_ones)
	{
		$form_def = new stdClass();

		if(in_array('fields', $which_ones)) {
			$form_def->fields = array_keys($this->form_fields);
		}
		if(in_array('rules', $which_ones)) {
			$form_def->rules = $this->get_field_validation_rules();
		}	
		if(in_array('specs', $which_ones)) {
			$form_def->specs = $this->form_fields;
		}	
		if(in_array('load_key', $which_ones)) {
			$form_def->load_key = $this->get_load_key();
		}	
		if(in_array('enable_spec', $which_ones)) {
			$form_def->enable_spec = $this->get_enable_field_specifications();
		}
		if(in_array('entry_commands', $which_ones)) {
			$form_def->entry_commands = $this->entry_commands;
		}
		return $form_def;
	}

	// --------------------------------------------------------------------
	function get_config_name()
	{
		return $this->config_name;
	}

	// --------------------------------------------------------------------
	function get_config_source()
	{
		return $this->config_source;
	}

	// --------------------------------------------------------------------
	// return the mapping between fields from the given external source
	// the form fields for the source for this instantiated object
	function get_external_source_field_map($source_name)
	{
		if(array_key_exists($source_name, $this->external_sources)) {
			return $this->external_sources[$source_name];
		} else {
			return FALSE;
		}
	}

	// --------------------------------------------------------------------
	// return the field defined as key for spreadsheet loading
	private
	function get_load_key()
	{
		$load_key = '';
		// look for specific definintion from config db
		foreach($this->form_fields as $field => $spec) {
			if(array_key_exists('load_key_field', $spec)) {
				$load_key = $field;
				break;
			}
		}
		// default is first field that is not no-edit or hidden
		if(!$load_key) {
			foreach($this->form_fields as $field => $spec) {
				if($spec['type'] != "hidden" and $spec['type'] != "non-edit") {
					$load_key = $field;
					break;
				}
			}
		}
		return $load_key;
	}

	// --------------------------------------------------------------------
	private
	function get_enable_field_specifications()
	{
		$specs = array();
		foreach($this->form_fields as $f_name => $f_spec) {
			if(array_key_exists('enable', $f_spec)) {
				$specs[$f_name] = $f_spec['enable'];
			}
		}
		return $specs;
	}
	
	// --------------------------------------------------------------------
	// return an array from the form field specifications keyed by
	// field name and containing the validation rules for the field
	// as the the value for the key
	private
	function get_field_validation_rules()
	{
		$rules = array();
		foreach($this->form_fields as $f_name => $f_spec) {
			$rule = array();
			$rule['field'] = $f_name;
			$rule['label'] =  $f_spec['label'];
			$rule['rules'] =  $f_spec['rules'];
			$rules[] = $rule;
		}
		return $rules;
	}

	// --------------------------------------------------------------------
	private
	function get_entry_form_definitions($config_name, $dbFileName)
	{
		$dbFilePath = $this->configDBFolder . $dbFileName;

		$dbh = new PDO("sqlite:$dbFilePath");
		if(!$dbh) throw new Exception('Could not connect to config database at '.$dbFilePath);

		// get list of tables in database
		$tbl_list = array();
		foreach ($dbh->query("SELECT tbl_name FROM sqlite_master WHERE type = 'table'", PDO::FETCH_ASSOC) as $row) {
			$tbl_list[] = $row['tbl_name'];
		}
		
		if(in_array('form_fields', $tbl_list)) {
			$this->form_fields = array();
			foreach ($dbh->query("SELECT * FROM form_fields", PDO::FETCH_ASSOC) as $row) {
				$a = array();
				$a['label'] = $row['label'];
				$a['type'] = $row['type'];
				$a['size'] = $row['size'];
				$a['rules'] = $row['rules' ];
				$a['maxlength'] = $row['maxlength'];
				$a['rows'] = $row['rows'];
				$a['cols'] = $row['cols'];
				$a['default'] = $row['default'];
				
				$this->form_fields[$row['name']] = $a;
			}
		}
		if(in_array('form_field_options', $tbl_list)) {
			foreach ($dbh->query("SELECT * FROM form_field_options", PDO::FETCH_ASSOC) as $row) {
				$this->form_fields[$row['field']][$row['type']] = $row['parameter'];
			}
		}

		if(in_array('form_field_choosers', $tbl_list)) {
			$fl = array();
			foreach ($dbh->query("SELECT * FROM form_field_choosers", PDO::FETCH_ASSOC) as $row) {
				$a = array();
				$a['type'] = $row['type'];
				$a['PickListName'] = $row['PickListName'];
				$a['Target'] = $row['Target'];
				$a['XRef'] = $row['XRef'];
				$a['Delimiter'] = $row['Delimiter'];
				if($row['Label'] != '') {
					$a['Label'] = $row['Label'];
				}
				$fl[$row['field']][] = $a;
			}
			foreach($fl as $fn => $ch) {
				if(count($ch) == 1) {
					$this->form_fields[$fn]['chooser_list'] = array($ch[0]);				
				} else {
					$this->form_fields[$fn]['chooser_list'] = $ch;				
				}
			}
		}

		if(in_array('operations_fields', $tbl_list)) {			
			$this->operations_fields = array();
			foreach ($dbh->query("SELECT * FROM operations_fields", PDO::FETCH_ASSOC) as $row) {
				$a = array();
				$a['label'] = $row['label'];
				$a['rules'] = $row['rules'];
				$this->operations_fields[$row['name']] = $a;
			}
		}
		
		if(in_array('entry_commands', $tbl_list)) {		
			$this->entry_commands = array();
			foreach ($dbh->query("SELECT * FROM entry_commands", PDO::FETCH_ASSOC) as $row) {
				$a = array();
				$a['type'] = $row['type'];
				$a['label'] = $row['label'];
				$a['tooltip'] = $row['tooltip'];
				$a['target'] = $row['target'];
	
				$this->entry_commands[$row['name']] = $a;
			}
		}
		
		if(in_array('external_sources', $tbl_list)) {
			$this->external_sources = array();
			foreach ($dbh->query("SELECT DISTINCT * FROM external_sources", PDO::FETCH_ASSOC) as $row) {
				$this->external_sources[$row['source_page']] = array();
			}
			foreach ($dbh->query("SELECT * FROM external_sources", PDO::FETCH_ASSOC) as $row) {
				$a = array();
				$tx = explode(".", $row['type']);
				$a['type'] = $tx[0];
				$a['value'] = $row['value'];
				if(count($tx) > 1) {
					$a[$tx[1]] = $tx[2];
				}
				$this->external_sources[$row['source_page']][$row['field']] = $a;
			}
		}

	}
	
}
?>