<?php
// manages construction of an entry form (in different formats)

class Entry_form {
// build entry form display

	private $form_field_specs = array();
	
	private $field_values = array();
	
	private $field_enable = array();

	private $field_errors = array();
	
	private $include_help_link = TRUE;
	
	private $file_tag = '';
	
	// --------------------------------------------------------------------
	function __construct()
	{
	}
	
	// --------------------------------------------------------------------
	function init($form_field_specs, $file_tag)
	{
		$this->form_field_specs = $form_field_specs;
		$this->file_tag = $file_tag;

		$this->set_field_values_to_default();
	}
	
	// --------------------------------------------------------------------
	// set current field values to defaults as defined by specs
	private
	function set_field_values_to_default()
	{
		$CI =& get_instance();
		$CI->load->helper('user');
		
		foreach($this->form_field_specs as $fld => $spc) {
			$this->field_values[$fld] = $this->get_default_value($spc); 
			$this->field_errors[$fld] = '';
		}
	}

	// --------------------------------------------------------------------
	// get default value for field from spec
	private
	function get_default_value($f_spec)
	{
		$val = '';
		if(isset($f_spec["default_function"])) {
			// if so, use specified function to get value
			$func_parts = explode(':', $f_spec["default_function"]);		
			switch($func_parts[0]) {
				case 'GetUser()':
					$val = get_user();
					break;
				case 'CurrentDate':
					$val = date("m/d/Y");
					break;
				case 'PreviousWeek':
					$val = date("m/d/Y", strtotime('-1 week'));
					break;
				case 'PreviousNDays':
					$interval = $func_parts[1] * -1;
					$val = date("m/d/Y", strtotime("$interval day"));
					break;
			}
		}  else {
			// otherwise, use the literal default
			$val = $f_spec["default"];
		}
		return $val;
	}
	
	// --------------------------------------------------------------------
	function set_field_value($field, $value)
	{
		$this->field_values[$field] = $value;		
	}

	// --------------------------------------------------------------------
	function set_field_error($field, $error)
	{
		$this->field_errors[$field] = $error;
	}

	// --------------------------------------------------------------------
	function set_field_enable($fields)
	{
		$this->field_enable = $fields;
	}
	
	// --------------------------------------------------------------------
	// build components for entry form into one of two arrays of rows,
	// one array for visible components of the form and the other for
	// invisible components, where each array row contains components 
	// for an entry form row, and then call function to build HTML for 
	// visible entry form
	function build_display()
	{
		$CI =& get_instance();
		$CI->load->model('dms_chooser', 'choosers');
		$CI->load->helper(array('url', 'string', 'form'));
		
		$visible_fields = array();
		$hidden_fields = array();
		$block_number = 0;
		foreach($this->form_field_specs as $fld => $spc) {
			if($spc['type'] == 'hidden') {
				$val = $this->field_values[$fld];
				$hidden_fields[] = "<input type='hidden' id='$fld' name='$fld' value='$val'/>";
			} else {
				// if field has section header attribute, add section header row to table
				if(array_key_exists('section', $spc)) {
					$block_number++;
					$visible_fields[] = array(-1, $spc['section'], $block_number); 
					// (someday) allow for enable field and section headers to be used together
				}
				$help = $this->make_wiki_help_link($spc['label']);
				$label = $spc['label'];
				$field = $this->make_entry_field($fld, $spc, $this->field_values[$fld]);
				$choosers = $this->make_choosers($fld, $spc);
				$error = $this->field_errors[$fld]; //$this->make_error_field($this->field_errors[$fld]);
				//
				$entry = $this->make_entry_area($field, $choosers, $error);
				$param = ($help)?$help . '&nbsp;' . $label:$label;
				//
				if(!empty($this->field_enable)) {
					$enable_ctrl = $this->make_field_enable_checkbox($fld);				
					$visible_fields[] = array($block_number, $param, $enable_ctrl, $entry);
				} else {
					$visible_fields[] = array($block_number, $param, $entry);
				}
			}
		}
		// magic command/mode field
		$attr = array('name' => 'entry_cmd_mode', 'id' => 'entry_cmd_mode', 'type' => 'hidden');
		$hidden_fields[] = form_input($attr);

		// package form display elements into final container
		$str = '';
		if(!empty($visible_fields)) {
			$str .= $this->display_table($visible_fields, empty($this->field_enable));
		}
		$str .= implode("\n", $hidden_fields);
		return $str;
	}	

	
	// ---------------------------------------------------------------------------------------------------------
	// HTML formatting function - maybe move to helper someday
	// ---------------------------------------------------------------------------------------------------------

	// --------------------------------------------------------------------
	// put content of visible fields into HTML table
	private
	function display_table($visible_fields, $has_enable_col)
	{
		$str = "";
		$str .= "<table class='EPag'>\n";
		
		$header = ($has_enable_col)?array('Parameter', 'Value'):array('Parameter', 'Enable', 'Value');
		$str .= "<tr>";
		foreach($header as $head) {
			$str .= "<th>".$head."</th>";
		}
		$str .= "</tr>\n";
		
		// place all visible fields into table cells in table rows
		foreach($visible_fields as $row) {
			// remove the section number from the row fields (we don't display it)
			$section_number = array_shift($row);
			// if row is a section header, apply header formatting to field and table row
			$col_span = '';
			if($section_number == -1){
				$col_span = "colspan='2'";
				$blk = array_pop($row); // retrieve and remove block number for section head
				$row[0] = $this->make_section_header($blk, $row[0]);
			}
			// define classes for section rows with section numbers greater than 0
			$class = '';
			if($section_number > 0) {
				$class = "class='section_block_$section_number section_block_all'";
			}
			// place row fields in table cells in table row
			$str .= "<tr $class>";
			foreach($row as $field) {
				$str .= "<td $col_span>".$field."</td>";
			}
			$str .= "</tr>\n";
		}
		$str .= "</table>\n";		
		return $str;
	}
	

	// -----------------------------------
	private
	function make_section_header($section_count, $section_label)
	{
		$s = "";
		$block_label = "section_block_$section_count";
		$marker = "<img id='".$block_label."_cntl"."' src='" . base_url(). "/images/z_hide_col.gif' border='0' >";
		$s .= "<a href='javascript:void(0)' onclick='showHideTableRows(\"$block_label\", \"".base_url()."/images/\", \"z_show_col.gif\", \"z_hide_col.gif\")'>$marker</a>";
		$s .= "&nbsp; <strong>".$section_label."</strong>";
		return $s;
	}

	// -----------------------------------
	private
	function make_field_enable_checkbox($fld)
	{
		$str = '';
		if(array_key_exists($fld, $this->field_enable)) {
			$ckbx_id = $fld . '_ckbx_enable';
			$click = "onClick='enableDisableField(this, \"$fld\")'";
			switch($this->field_enable[$fld]) {
				case 'enabled':
					$str = "<input type='checkbox' class='_ckbx_enable' name='$ckbx_id' $click checked='yes' >";
					break;
				case 'disabled':
					$str = "<input type='checkbox' class='_ckbx_enable' name='$ckbx_id' $click >";
					break;
				case 'none':
					$str = '';
					break;
			}
		}
		return $str;		
	}
	
	// -----------------------------------
	// package components of entry area
	private
	function make_entry_area($field, $choosers, $error)
	{
		$str = '';
		$str .= "<table>";
		$str .= "<tr>";
		$str .= "<td>".$field."</td>";
		$str .= "<td style='vertical-align:bottom'>".$choosers."</td>";
		$str .= "</tr>";
		if($error) {
			$str .= "<tr><td colspan='2'>".$error."</td></tr>";
		}
		$str .= "</table>";
		return $str;
	}

	// -----------------------------------
	// create a set of choosers from the list in the given field spec
	function make_choosers($f_name, $f_spec, $element_start = "<div style='margin-bottom:5px;'>", $element_end = "</div>")
	{
		$s = "";
		$seq = 0;
		if(array_key_exists("chooser_list", $f_spec)) {					
			foreach($f_spec['chooser_list'] as $chsr) {
				$seq++;
 				$pln = $chsr["PickListName"];
				$delim = $chsr['Delimiter'];
				$type = $chsr["type"];
				$target = $chsr['Target'];
				$xref = $chsr['XRef'];
				$label = (array_key_exists('Label', $chsr))?$chsr['Label']:'Choose from:';
				$ch = $this->make_chooser($f_name, $type, $pln, $target, $label, $delim, $xref, $seq);
				$s .= $element_start . $ch .$element_end;
			}
		}
		return $s;
	}

	// -----------------------------------
	// create a chooser from the given parameters
	private
	function make_chooser($f_name, $type, $pln, $target, $label, $delim, $xref, $seq)
	{
		$CI =& get_instance();
		return $CI->choosers->make_chooser($f_name, $type, $pln, $target, $label, $delim, $xref, $seq);
	}
	
	// -----------------------------------
	// 
	private
	function make_entry_field($f_name, $f_spec, $cur_value)
	{
		$s = "";
	
		// set up delimiter for lists for the field
		$delim = (isset($f_spec['chooser']['Delimiter']))?$f_spec['chooser']['Delimiter']:'';
		$delim = ($delim != '')?$delim:',';

		$data['name']  = $f_name;
		$data['id']  = $f_name;
		$data['value'] = $cur_value;
		
		// create HTML according to field type
		switch ($f_spec['type']) {
	
		case 'text':
			$data['maxlength'] = $f_spec['maxlength'];
			$data['size']      = $f_spec['size'];
			$s .= form_input($data);
			break;
	
		case 'area':
			$data['rows'] = $f_spec['rows'];
			$data['cols'] = $f_spec['cols'];
			if(isset($f_spec['auto_format'])) {
				if($f_spec['auto_format'] == 'xml') {
					$data['onBlur'] = "formatXMLText('".$data['id']."')";					
				}
			} else {
				$data['onChange'] = "convertList('".$data['id']."', '".$delim."')";
			}
			$s .= form_textarea($data);
			break;
	
		case 'non-edit':
			$s .= '<input type="hidden" name="' . $data['name'] . '" value="' . $data['value'] . '" id="' . $data['id'] . '" />';
			$s .= $data['value'];
			break;
	
		case 'hidden':
			$s .= "<input type='hidden' id='$f_name' name='$f_name' value='xx'/>";
//			$s .= form_hidden($data['name'], $data['value']);
			break;
	
		case 'file':
			$data['maxlength'] = $f_spec['maxlength'];
			$data['size']      = $f_spec['size'];
			$s .= form_upload($data);
			break;
			
		case 'checkbox':
			$checked = ($data['value'])?"checked='checked'":"";		
			$s .= "<input type='checkbox' name='$f_name' id='$f_name' value='Yes' $checked />Enabled<br/>";
			break;			
		}	
		return $s;
	}
	
	// -----------------------------------
	// 
	private
	function make_wiki_help_link($label)
	{
		$s = "";
		if($this->include_help_link) {
			$file_tag = $this->file_tag;
			$nsLabel = str_replace(" ", "_", $label);
			$CI =& get_instance();
			$pwiki = $CI->config->item('pwiki');
			$wiki_helpLink_prefix = $CI->config->item('wikiHelpLinkPrefix');
			$href = "${pwiki}${wiki_helpLink_prefix}${file_tag}#${nsLabel}";
			$s .= "<a class=help_link target = '_blank' title='Click to explain field ".$label."' href='".$href."'><img src='" . base_url(). "/images/help.png' border='0' ></a>";
		}
		return $s;
	}

	// -----------------------------------
	function get_mode_from_page_type($page_type)
	{
		return ($page_type == 'edit')?'update':'add';
	}
	
	// -----------------------------------
	function make_entry_commands($entry_commands, $page_type)
	{
		$str = '';

		// default command button
		$mode = $this->get_mode_from_page_type($page_type);
		$url = site_url(). $this->file_tag . "/submit_entry_form";
		$attributes['onclick'] = "updateEntryPage('$url', '$mode')";
		$attributes['content'] = ($page_type == 'create')?'Create':'Update';;
		//
		// is there an override for the default command button?
		foreach($entry_commands as $command => $spec) {
			if($spec['type'] == 'override' and $spec['target'] == $mode) {
				$attributes['onclick'] = "updateEntryPage('$url', '$command')";
				$attributes['content'] = $spec['label'];
				$attributes['title'] = $spec['tooltip'];
				break;
			}
		}
		$str .= form_button($attributes) . "<br>\n";

		// supplemental commands
		foreach($entry_commands as $command => $spec) {
			switch($spec['type']){
				case "cmd":
					$attributes = array();
					$attributes['content'] = $spec['label'];
					$attributes['onclick'] = "updateEntryPage('$url', '$command')";
					$attributes['title'] = $spec['tooltip'];
					$str .= form_button($attributes) . "<br>\n";
					break;
				case "retarget":
					$target_url = site_url().$spec['target'];
					$attributes = array();
					$attributes['content'] = $spec['label'];
					$attributes['onclick'] = "submitEntryPage('$target_url', '$command')";
					$attributes['title'] = $spec['tooltip'];
					$str .= form_button($attributes) . "<br>\n";
					break;
			}
		}
		return $str;
	}

	// ---------------------------------------------------------------------------------------------------------
	// form field adjustment section
	// ---------------------------------------------------------------------------------------------------------

	// --------------------------------------------------------------------
	// modify field specs to account for field edit permissions
	function adjust_field_permissions($userPermissions)
	{
		// look at each field
		foreach($this->form_field_specs as $f_name => $f_spec) {
			// find ones that have permission restrictions
			if(array_key_exists('permission', $f_spec)) {
				// do user's permisssions satisfy field restrictions?
				$fieldPermissions = explode(',', $f_spec['permission']);
				$hits = array_intersect($fieldPermissions, $userPermissions);
				// no - change spec to make field non-editable
				// and remove chooser (if one exists)
				if(count($hits) == 0) {
					$this->form_field_specs[$f_name]['type'] = 'non-edit';
					if(array_key_exists('chooser_list', $f_spec)) {
						unset($this->form_field_specs[$f_name]['chooser_list']);					
					}
				}
			}
		}		
	}

	// --------------------------------------------------------------------
	// change the visibility of designated fields according to given entry mode
	function adjust_field_visibility($mode)
	{
		foreach($this->form_field_specs as $f_name => $f_spec) {
			if(array_key_exists('hide', $f_spec)) {
				if($mode == $f_spec["hide"]) {
					$this->form_field_specs[$f_name]["type"] = "hidden";
				}
			}
		}		
	}
	
}
?>