<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
						$pat = '/(\[Req:[^\]]*\])/';
						$ms = array();
						preg_match_all($pat, $field, $ms);
						if(count($ms[0]) != 0) {
							$s .= $ms[0][count($ms[0])-1];
						}
						$pat = '/(DTA:[a-zA-Z0-9_#\-]*)/';
						preg_match($pat, $field, $ms);
						if(count($ms) > 1) {
							$s .= " " . $ms[1];
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
			if($seg_val === FALSE) break;
			if($seg_val != '-') {
				$a[$field] = convert_special_values($seg_val);
			}
			$seg_val = next($segs);
		}
		return $a;
	}

	// --------------------------------------------------------------------
	function convert_special_values($value)
	{
		switch($value) {
			case "__ThisYear__":
				$value = date("Y");
				break;
			case "__LastYear__":
				$value = date("Y", strtotime("last year"));
				break;
			case "__ThisMonth__":
				$value = date("n");
				break;
			case "__LastMonth__":
				$value = date("n", strtotime("last month"));
				break;
			case "__ThisWeek__":
				$value = date("W");
				break;
			case "__LastWeek__":
				$value = date("W", strtotime("last week"));
				break;
		}
		return $value;
	}

	// --------------------------------------------------------------------
	function entry_outcome_message($message, $option = 'success', $id='')
	{
		$str = '';
		$id = ($id)?" id='$id'":'';
		switch($option) {
			case 'success':
				$str =  "<div class='EPag_message' $id>" . $message . "</div>";
				break;
			case 'failure':
				$str =  "<div class='EPag_error' $id>" . $message . "</div>";
				break;
			case 'error':
				$str =  "<div class='bad_clr' $id>" . $message . "</div>";
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
	
?>
