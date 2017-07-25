<?php  
	if (!defined('BASEPATH')) {
		exit('No direct script access allowed');
	}

	// support functions for entry page features of base_controller

	// --------------------------------------------------------------------
	// get initial values for entry fields - URL will tell us where to get them
	// - defaults from config db
	// - current values from same entity type being edited
	// - current values from other entity type (if config db provides column mapping spec)
	// - URL segment values
	function get_initial_values_for_entry_fields($segs, $config_source, $form_field_names)
	{		
		$CI =& get_instance();

		$initial_field_values = array();

		$num_segs = count($segs);
		//
		if($num_segs == 0) {
			// just accept defaults
		} else
		if($num_segs == 1) {
			// get values from database using source and id that we were given
			$id = $segs[0];
			$CI->cu->load_mod('q_model', 'input_model', 'entry_page', $config_source);
			$initial_field_values =  $CI->input_model->get_item($id);
			
		} else
		if($num_segs > 1) {
			// get values from an external source
			$source = $segs[0];
			$id = $segs[1];
			if($source == 'init') {
				$segs = array_slice($segs, 1);
				// get values from url segments:
				$initial_field_values = get_values_from_segs($form_field_names, $segs);
			} else
			if($source == 'post') {
				// (someday) get values from POST
			} else {
				// get external source mapping
				$col_mapping = $CI->form_model->get_external_source_field_map($source);
				if($col_mapping) {
					// get values from database using source and id plucked from url
					$CI->cu->load_mod('q_model', 'input_model', 'detail_report', $source);
					$source_data =  $CI->input_model->get_item($id);
					$initial_field_values = load_from_external_source($col_mapping, $source_data);
				}
			}
		}
		return $initial_field_values;
	}

	// --------------------------------------------------------------------
	// return an array (of field => value) containing fields defined 
	// in $col_mapping with values according to type of mapping defined
	function load_from_external_source($col_mapping, $source_data)
	{
		$CI =& get_instance();

		$a = array();
		// load entry fields from external source
		foreach($col_mapping as $fld => $spec) {
			switch($spec['type']) {
				case 'ColName':
					$a[$fld] = $source_data[$spec['value']];
					break;
				case 'PostName':
					$pv = $CI->input->post($spec['value']);
					$a[$fld] = $pv;
					break;
				case 'Literal':
					$a[$fld] = $spec['value'];
					break;
			}
			// any further actions?
			if(isset($spec['action'])) {
				switch($spec['action']) {
					case 'Scrub':
						$s = "";
						$field = $a[$fld];
						
						$patReq = '/(\[Req:[^\]]*\])/';
						$matches = array();
						preg_match_all($patReq, $field, $matches);
						if(count($matches[0]) != 0) {
							$s .= $matches[0][count($matches[0])-1];
						}
						
						$patDTA = '/(DTA:[a-zA-Z0-9_#\-]*)/';
						preg_match($patDTA, $field, $matches);
						if(count($matches) > 1) {
							$s .= " " . $matches[1];
						}
						$a[$fld] = $s;
						break;
				}
			}
		}
		return $a;
	}
	
	// --------------------------------------------------------------------
	// override default values with values directly from url segments 
	// (based on matching segment and field order)
	function get_values_from_segs($form_field_names, $segs)
	{
		$a = array();	
		$seg_val = current($segs);
		foreach($form_field_names as $field) {
			if($seg_val === FALSE) {
				break;
			}
			
			if($seg_val != '-') {
				$a[$field] = convert_special_values($seg_val);
			}
			$seg_val = next($segs);
		}
		return $a;
	}

	/**
	 * Check for the field either matching a special tag or containing a special tag
	 * String comparisons are case sensitive
	 * @param type $value
	 * @return type
	 */	
	function convert_special_values($value)
	{
		// Check the field fully matching a special tag
		switch($value) {
			case "__ThisYear__":
				return date("Y");
			case "__LastYear__":
				return date("Y", strtotime("last year"));
			case "__ThisMonth__":
				return  date("n");
			case "__LastMonth__":
				return date("n", strtotime("last month"));
			case "__ThisWeek__":
				return date("W");
			case "__LastWeek__":
				return date("W", strtotime("last week"));
		}
		
		// Check for special tags at the start
		if (startsWith($value, "StartsWith__")) {
			// Use a backtick to signify that the value must start with the value
			$newValue = str_replace("StartsWith__", "`", $value);
		} else if (startsWith($value, "ExactMatch__")) {
			// Use a tilde to signify that the value must exactly match the value
			$newValue = str_replace("ExactMatch__", "~", $value);
		} else if (startsWith($value, "NoMatch__")) {
			// Use a colon to signify that the value cannot contain the value
			$newValue = str_replace("NoMatch__", ":", $value);
		} else {
			$newValue = $value;
		}
		
		// Check for the special Wildcard tag in the middle (allow both __Wildcard__ and __WildCard__)
		// If found, replace with a percent sign to signify a wildcard match
		$finalValue = str_ireplace("__Wildcard__", "%", $newValue);
			
		return $finalValue;
	}

	// --------------------------------------------------------------------
	function entry_outcome_message($message, $option = 'success', $id='')
	{
		$str = '';
		$idWithTag = ($id)?" id='$id'":'';
		switch($option) {
			case 'success':
				$str =  "<div class='EPag_message' $idWithTag>" . $message . "</div>";
				break;
			case 'failure':
				$str =  "<div class='EPag_error' $idWithTag>" . $message . "</div>";
				break;
			case 'error':
				$str =  "<div class='bad_clr' $idWithTag>" . $message . "</div>";
				break;
			default:
				$str =  "<div${id}>" . $message . "</div>";
				break;
		}
		return $str;
	}

	// --------------------------------------------------------------------
	// make post-submission links to list report and detail report
	// "Go to list report" link is made by default if report action exists (unless overridden by "link_tag")
	// detail_id - Makes "Go to detail report" link (if show action exists) using the specified entry page field as the identifier (unless suppressed by "link_tag")
	// link_tag  - Makes default "Go to list report" link point to a list report in a different page family, and prevents "Go to detail report" link from appearing
	// link	     - Adds an arbitrary link to the entry page that appears following successfully submitting the entry
	
	function make_post_submission_links($tag, $ps_link_specs, $input_params, $actions)
	{
		$lr_tg = '';		
		$dr_tag = '';
		$id = '';

		// get base url tag for post submission list report link
		if($ps_link_specs['link_tag'] != '') {
			$lr_tg = $ps_link_specs['link_tag'];
		} else
		if($actions['report']) {
			$lr_tg = $tag;
		} else {
			$lr_tg = '';		
		}
		// get base url tag for post submission detail report link
		if($actions['show'] && $ps_link_specs['link_tag'] == '') {
			$dr_tag = $tag;
		} else {
			$id = '';
			$dr_tag = '';
		}
		// get id for post submission link
		if($ps_link_specs['detail_id'] != '') {
			$argName = $ps_link_specs['detail_id'];
			$id = $input_params->$argName;
		}
		$x_tag = ($ps_link_specs['link'] != '')?json_decode($ps_link_specs['link'], true):null;
	
		// make the HTML
		$links = "";
		if($lr_tg != ''){
			$url = site_url().$lr_tg."/report";
			$links .= "&nbsp; <a href='$url'>Go to list report</a>";			
		}
		if($dr_tag != '' && $id != '') {
			$url = site_url().$dr_tag."/show/".$id;
			$links .= "&nbsp; <a href='$url'>Go to detail report</a>";
		}
		if($x_tag != null) {
			$url = site_url().$x_tag["link"].$id;
			$links .= "&nbsp; <a href='$url'>".$x_tag["label"]."</a>";
		}
		return $links;	
	}
	
	function startsWith($haystack, $needle)
	{
		 $length = strlen($needle);
		 return (substr($haystack, 0, $length) === $needle);
	}	
