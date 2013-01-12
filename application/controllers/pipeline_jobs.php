<?php
require("base_controller.php");

class pipeline_jobs extends Base_controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "pipeline_jobs";
		$this->my_title = "Pipeline Jobs";
	}
	
	// --------------------------------------------------------------------
	// return supplemental form for job parameter editing
	// using either actual parameter values from existing entity, 
	// or defaults based on lookup using key
	// AJAX
	// TODO: generic enough for libraries/entry.php? Or new library?
	function parameter_form($id = 0, $default_key = '')
	{
		// get basic parameter definitions using the lookup key
		$def_params = array();
		if($default_key) {
			$xml_def = $this->get_param_definitions($default_key, $this->my_tag);
			$def_params = $this->extract_params_from_xml($xml_def);
		}

		// if an existing entity is involved, get its actual parameters
		$val_params = array();
		if($id != 0) { // lookup parameter set from script - use lightweight query
			$xml_val = $this->get_param_values($id, $this->my_tag);
			$val_params = $this->extract_params_from_xml($xml_val);
		}
		
		// any special display features?
		$display_params = array();
		// TODO: special display definitions in config db

		$params = $this->merge_params($def_params, $val_params, $display_params);
	
		// use parameter set XML to build supplemental form
		if(!empty($params)) {
			echo $this->build_param_entry_form($params, $default_key);
		} else {
			$lnk = "<a href='javascript:void(0)' onclick='entry.pipeline_jobs.load_param_form()' >here</a>";
			if(!$default_key) {
				$scripts = $this->get_scripts_with_param_definitions($this->my_tag);
				echo "<div>Click one of the scripts below to show form for entering parameters:</div>";
				echo "<ul>";
				foreach($scripts as $dt) {
					$script = $dt['Script'];
					echo "<li><a href='javascript:void(0)' onclick='entry.pipeline_jobs.choose_script(\"$script\")'>$script</a></li>";
				}
				echo "</ul>";
				echo "<div>You may also manually enter a script name and click $lnk to show the parameter form (if the script allows that)</div>";
			} else {
				echo "<div>There are no parameter definitions for script '$default_key'.  You may change the script and click $lnk to try again.</div>";
			}
		}
	}

	// --------------------------------------------------------------------
	// get list of scripts with parameters defined
	private
	function get_scripts_with_param_definitions($config_source, $config_name = 'parameter_scripts')
	{
		$this->cu->load_mod('q_model', 'swp_model', $config_name, $config_source);
		$query = $this->swp_model->get_rows('filtered_and_paged');
		return $query->result_array();
	}
	
	// --------------------------------------------------------------------
	// merge parameter definitions, current values, and special display definitions
	// into single array of parameter field definitions
	// TODO: move this to some other module
	private
	function merge_params($def_params, $val_params, $display_params)
	{
		$p = $def_params;
		
		foreach($val_params as $key => $def) {
			if(array_key_exists($key, $p)) {
				$p[$key]['value'] = $def['value'];
			} else {
				$def['Reqd'] = '??';
				$p[$key] = $def;
			}
		}
		foreach($p as $key => &$def) {
			if(array_key_exists($key, $display_params)) {
				// ??
			} else {
				// default field display parameters
				$def['type'] = 'text';
				$def['size'] = '120';
			}
		}
		return array_values($p);
	}
	
	// --------------------------------------------------------------------
	// TODO: move this to some other module
	private
	function get_param_values($id, $config_source, $config_name = 'parameter_values')
	{
		$xml = '';
		if($id) {
			$this->cu->load_mod('q_model', 'data_model', $config_name, $config_source);
			$result_row = $this->data_model->get_item($id);
			$xml = $result_row['params'];
		}
		return $xml;
	}
	
	// --------------------------------------------------------------------
	// get definition of parameters for key
	// TODO: move this to some other module
	private
	function get_param_definitions($id, $config_source, $config_name = 'parameter_definitions')
	{
		$xml = '';
		if($id) {
			$this->cu->load_mod('q_model', 'def_model', $config_name, $config_source);
			$result_row = $this->def_model->get_item($id);
			$xml = $result_row['params'];
		}
		return $xml;
	}

	// --------------------------------------------------------------------
	// get array of parameters from given XML
	// with section/name/value properties for each parameter
	// TODO: move this to some other module
	private
	function extract_params_from_xml($xml)
	{
		$result = array();
		if($xml) {
			$dom = new DomDocument();
			$dom->loadXML('<root>'.$xml.'</root>');
			$xp = new domxpath($dom);
			$params = $xp->query("//Param");
		
			foreach ($params as $param) {
				$a = array();
				$a['name'] = $param->getAttribute('Name');
				$a['value'] = $param->getAttribute('Value');
				$a['section'] = $param->getAttribute('Section');
				$a['Reqd'] = $param->getAttribute('Reqd');
				$a['step'] = $param->getAttribute('Step');
				$a['user'] = $param->getAttribute('User');
				$key = $a['section'].'|'.$a['name'];
				$result[$key] = $a;
			}
		}
		return $result;
	}
	
	// --------------------------------------------------------------------
	// given array of parameters, return HTML 
	// for supplemental form to edit them
	// TODO: move this to some other module (libraries/entry_form?)
	private
	function build_param_entry_form($params, $script)
	{
		$str = "";
		$section_header = "";
		$header_style = "font-weight:bold;";
		if(!empty($params)) {

			$show_class = 'show_input';
			$hide_class = 'hide_input';
			$vis_controls = $this->build_visibility_controls($show_class, $hide_class);
			
			$str .= "<table class='EPag'>\n";
			
			$str .= "<tr><td colspan='4' style='height:2em; padding-left: 1em; vertical-align: middle;'>$vis_controls</td></tr>";
			
			$str .= "<tr>";
			$str .= "<th>Parameter Name</th>";
			$str .= "<th>Req'd</th>";
			$str .= "<th>Parameter Value</th>";
			$str .= "<th>Step Lock</th>";
			$str .= "</tr>\n";
						
			foreach($params as $param) {
				$value = $param['value'];
				$name = $param['name'];
				$section = $param['section'];
				$type = $param['type'];
				$size = $param['size'];
				$step =($param['step'])?"Yes (" . $param['step'] . ")":"";
				$sectionName = ($step)?"$section.$name.$step":"$section.$name";
				
				if($section_header != $section) {
					$str .= "<tr><td colspan='4'><span style='$header_style'>$section</span></td></tr>";
				}
				$section_header = $section;

				$json = '{ "name":"'.$name.'", "value":"'.$value.'", "section":"'.$section.'"}';
				$class = ($param['user'] == "Yes")?"class='$show_class'":"class='$hide_class'";
				$highlight = (($param['user'] == "Yes"))?" style='color:blue;'":"";
				$help_link = $this->build_wiki_help_link($script, $param['name']);

				// place row fields in table cells in table row
				$str .= "<tr $class>";
				$str .= "<td>${help_link}<span $highlight> " . $param['name'] . "</span></td>";
				$str .= "<td>".$param['Reqd']."</td>";
				switch($type) {
					case 'text':
						$str .= "<td><input name='$sectionName' size='$size' maxlength='4096' value='$value' /></td>";
						break;
				}
				$str .= "<td>".$step."</td>";
				$str .= "</tr>\n";
			}
		}
		$str .= "</table>\n";	
		return $str;
	}

	// --------------------------------------------------------------------
	// build controls for collapsing and expanding parameter form
	private
	function build_visibility_controls($show_class, $hide_class)
	{
		$str = "";
		$str .= "<a href=\"javascript:entry.pipeline_jobs.set_param_row_visibility('$hide_class', 'none')\">Collapse</a> to essentials &nbsp; &nbsp;";
		$str .= "<a href=\"javascript:entry.pipeline_jobs.set_param_row_visibility('$hide_class', 'table-row')\">Expand</a> to show all";
		return $str;
	}

	// -----------------------------------
	// 
	private
	function build_wiki_help_link($script, $label)
	{
		$s = "";
		$file_tag = $this->my_tag;
		$nsLabel = str_replace(" ", "_", $label);
		$CI =& get_instance();
		$pwiki = $CI->config->item('pwiki');
		$wiki_helpLink_prefix = $CI->config->item('wikiHelpLinkPrefix');
		$href = "${pwiki}${wiki_helpLink_prefix}${file_tag}_${script}#${nsLabel}";
		$s .= "<a class=help_link target = '_blank' title='Click to bring up PRISM Wiki help page' href='".$href."'><img src='" . base_url(). "/images/help.png' border='0' ></a>";
		return $s;
	}	
}
?>