<?php

class Dms_chooser extends Model {

	// this array defines the selection list choosers
	// and includes both cases (direct list of options vs query db for options)
	var $choices = array();
	
	// --------------------------------------------------------------------
	function __construct() 
	{
		//Call the Model constructor
		parent :: Model();
		$this->load_choosers();
	}

	// --------------------------------------------------------------------
	// initialize the list of choosers from the config db file
	function load_choosers()
	{
		$dbFilePath = 'system/application/model_config/dms_chooser.db';
		$dbh = new PDO("sqlite:$dbFilePath");
		$r = $dbh->query("SELECT * FROM chooser_definitions", PDO::FETCH_ASSOC);
		foreach ($r as $row) {
			$def = array();
			$def['db'] = $row['db'];
			$type = $row['type'];
			$def['type'] = $type;
			switch($type) {
				case 'select';
					$def['value'] = json_decode($row['value'], TRUE);
				break;
				case 'sql';
					$def['value'] = $row['value'];
				break;
			}
			$this->choices[$row['name']] = $def;
		}
	}

	// --------------------------------------------------------------------
	// returns list of chooser specs
	function get_choosers()
	{
		return $this->choices;
	}

	// --------------------------------------------------------------------
	// returns sorted list of chooser names
	function get_chooser_names()
	{
		$cl = array_keys($this->choices);
		natcasesort($cl);
		return $cl;
	}
	// --------------------------------------------------------------------
	// returns choices list for given chooser
	function get_choices($chooser_name)
	{
		$options = array();
		if(array_key_exists($chooser_name, $this->choices)) {
			switch($this->choices[$chooser_name]["type"]) {
				case "select":
					$options[""] = "-- choices --";
					foreach($this->choices[$chooser_name]["value"] as $k=>$v) {
						$options[$k] = $v;
					}
					break;
				case "sql":
					$db = "default";
					if(array_key_exists("db", $this->choices[$chooser_name])) {
						$db = $this->choices[$chooser_name]["db"];
					}
					$my_db = $this->load->database($db, TRUE);
					$result = $my_db->query($this->choices[$chooser_name]["value"]);					
					if($result) {
						$options[""] = "-- choices --";
						foreach($result->result_array() as $row) {
							$val = $row["val"];
							$ex = (string)$row["ex"];
							$ex = ($ex != '')?$ex:$val;
							$options[$ex] = $row["val"];
						}
					}
					break;
			}		
		}
		return $options;
	}
	// --------------------------------------------------------------------
	// this returns HTML for a drop-down selector and suitable options
	// for the specified chooser_name.
	function get_chooser($target_field_name, $chooser_name, $mode = 'replace', $seq = '')
	{
		$str = "";
		$chooser_element_name = $target_field_name . "_chooser" . $seq;
		$js = "id=\"$chooser_element_name\" onChange='setFieldValueFromSelection(\"$target_field_name\", \"$chooser_element_name\", \"$mode\")'";
		if(!array_key_exists($chooser_name, $this->choices)) {
			$str .=  "The chooser name '$chooser_name' could not be found";
			return $str;
		}
		switch($this->choices[$chooser_name]["type"]) {
			case "select":
				$options = $this->get_choices($chooser_name);
				$str .=  form_dropdown($chooser_element_name, $options, '', $js);
				return $str;
			case "sql":
				$options = $this->get_choices($chooser_name);
				$str .=  form_dropdown($chooser_element_name, $options, '', $js);
				break;
			default:
				$str .=  "The chooser type was not recognized.";
				break;
		}
		return $str;
	}

	// -----------------------------------
	// create a chooser from the given parameters
	function make_chooser($f_name, $type, $pln, $target, $label, $delim, $xref, $seq = '1')
	{
		$str = "";
		switch($type){
			case "picker.append":
				$mode = ($delim==',')?'append_comma':'append';
			case "picker.replace":
				$mode = (isset($mode))?$mode:'replace';
				$str .= "$label ".$this->get_chooser($f_name, $pln, $mode, $seq);
				break;	
			case "list-report.helper":
				$CI =& get_instance();
				$CI->load->helper(array('string'));
				$target_url = reduce_double_slashes(site_url().$target);
				$str .= "$label <a href=\"javascript:callChooser('$f_name', '$target_url', '$delim', '$xref')\"><img src='".base_url()."images/chooser.png' border='0'></a>";
				break;	
			case "picker.prevDate":
				$str .= "$label <a href=\"javascript:callDatepicker('$f_name')\"><img src='".base_url()."images/date.png' border='0'></a>";
				break;	
		}
		return $str;
	}
	
}
?>
