<?php  
	if (!defined('BASEPATH')) {
		exit('No direct script access allowed');
	}

	// --------------------------------------------------------------------
	function make_search_filter_minimal($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter)
	{	
		$g = make_paging_filter($current_paging_filter_values);
		$p = make_primary_filter($current_primary_filter_values);
		$s = make_secondary_filter($sec_filter_display_info);
		$r = make_sorting_filter($current_sorting_filter_values, $cols);
		$c = make_column_filter($cols, $col_filter, 6);
		
		$style = 'display:none;float:left;padding:3px 3px 0 0;';


		$g = "<div style='display:none' > $g </div>";
		$p = "<div id='primary_filter_container' class='filter_container_box' class='filter_container_box' style='clear:both;' > $p </div>";
		$s = "<div id='secondary_filter_container' class='filter_container_box' style='$style' > $s </div>";
		$r = "<div id='sorting_filter_container' class='filter_container_box' style='$style' > $r </div>";
		$c = "<div id='column_filter_container' class='filter_container_box' style='$style' > $c </div>";
		
		echo $g;
		echo "<div style='height:3px;' ></div>";
		echo $p;
		echo $s;
		echo $r;	
		echo $c;
	}

	// --------------------------------------------------------------------
	function make_search_filter_expanded($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter, $filter_display_mode = "")
	{
		$big_primary_filter = big_primary_filter($current_primary_filter_values);
//		$col_filter_size = ($big_primary_filter)?14:7;
		$col_filter_size = 6;
		$col_filter_size = (count($cols) < $col_filter_size)?count($cols):$col_filter_size;

		$g = make_paging_filter($current_paging_filter_values);
		$p = make_primary_filter_in_table($current_primary_filter_values);
		$s = make_secondary_filter($sec_filter_display_info);
		$r = make_sorting_filter($current_sorting_filter_values, $cols);
		$c = make_column_filter($cols, $col_filter, $col_filter_size);
		
		$style = ($filter_display_mode) ? "style='display:none;'" : "";

		$g = "<div style='display:none' > $g </div>";
		$p = "<div id='primary_filter_container' class='filter_container_box' > $p </div>";
		$s = "<div id='secondary_filter_container' class='filter_container_box' $style > $s </div>";
		$r = "<div id='sorting_filter_container' class='filter_container_box' $style > $r </div>";
		$c = "<div id='column_filter_container' class='filter_container_box' $style > $c </div>";

		// set up table to hold fields
		list($cell_s, $cell_vs, $cell_f) = array('<td style="vertical-align:top;">', '<td style="vertical-align:top;" rowspan="2">', "</td>\n");		
		list($row_s, $row_f) = array("<tr>", "</tr>\n");		

		$str = "<table id='search_filter_table' >\n";
		if($big_primary_filter) {
			$str .= $row_s;
			$str .= $cell_vs . $p . $cell_f;
			$str .= $cell_s . $s . $cell_f;
			$str .= $cell_vs . $c . $cell_f;
			$str .= $row_f;
			$str .= $row_s;
			$str .= $cell_s . $r . $cell_f;
			$str .= $row_f;
		} else {
			$str .= $row_s;
			$str .= $cell_s . $p . $cell_f;
			$str .= $cell_s . $s . $cell_f; 
			$str .= $cell_s . $r . $cell_f;
			$str .= $cell_s . $c . $cell_f;
			$str .= $row_f;			
		}
		$str .= "</table>\n";
				
		echo $g;
		echo $str;
	}

		// --------------------------------------------------------------------
	function make_param_filter($cols, $current_paging_filter_values, $current_sorting_filter_values, $col_filter)
	{
		$style = 'float:left;padding:3px 3px 0 0;display:none;';
		
		$g = make_paging_filter($current_paging_filter_values);
		$g = "<div style='display:none' > $g </div>";
		$r = 'x';
		if(!empty($cols)) {
			$r = make_sorting_filter($current_sorting_filter_values, $cols);
			$c = make_column_filter($cols, $col_filter, 6);
			$r = "<div id='sorting_filter_container' class='filter_container_box' style='$style' > $r </div>";
			$c = "<div id='column_filter_container' class='filter_container_box' style='$style' > $c </div>";
		}
	
		echo $g;
		if(!empty($cols)) {
			echo "<div style='height:3px;clear:both;' ></div>";
			echo $r;	
			echo $c;
		}
	}
	
	// --------------------------------------------------------------------
	function big_primary_filter($current_primary_filter_values)
	{
		if(count($current_primary_filter_values) > 5) {
			return TRUE;
		}

		$big = FALSE;
		foreach($current_primary_filter_values as $id => $spec) {
			if(array_key_exists("chooser_list", $spec)) {
				$big = TRUE;
				break;
			}
		}
		return $big;
	}	

	// --------------------------------------------------------------------
	function make_intermediate_expansion_control() 
	{
		return 	'<a class="cmd_link_a" href="javascript:void(0)" onclick="lstRep.updateMyFilter(\'intermediate\')" title="Expand showing only the primary filter"><span class="expando_section ui-icon ui-icon-circle-zoomout "></span></a>';
	}
	// --------------------------------------------------------------------
	function make_intermediate_collapse_control() 
	{
		return 	'<a class="cmd_link_a" href="javascript:void(0)" onclick="lstRep.updateMyFilter(\'minimal\')" title="Minimize filters"><span class="expando_section ui-icon ui-icon-circle-zoomin "></span></a>';
	}
	
	
	// --------------------------------------------------------------------
	// primary filter form fields 
	function make_primary_filter($primary_filter_defs)
	{		
		// get CI instance
		$CI =& get_instance();
		$CI->load->helper('form');

		$str = "";

		list($row_s, $row_f) = array("<span>", "</span>\n");		
		list($cell_s, $cell_vs, $cell_f) = array("<span>", "<span>", "</span>");		
		
		foreach($primary_filter_defs as $id => $spec) {
			$data['id'] = $id;
			$data['name'] = $data['id'];
			$data['class'] = 'primary_filter_field filter_input_field';
			$data['size'] =  10; //($spec["size"] < $data['size'])?$spec["size"]:$data['size'];
            $data['maxlength'] = '100';
			$data['value'] = $spec["value"];
			$str .= $row_s . $cell_s . str_replace(" ", "&nbsp;", $spec["label"]) . "&nbsp;" . $cell_f . $cell_vs . form_input($data). $cell_f . $row_f;
		}
		$str .= make_intermediate_expansion_control();
		return $str;
	}

	// --------------------------------------------------------------------
	// primary filter form fields
	function make_primary_filter_in_table($primary_filter_defs)
	{		
		// get CI instance
		$CI =& get_instance();
		$CI->load->helper('form');
		$CI->load->library('entry_form');
		$CI->load->model('dms_chooser', 'choosers');

		$str = '';
		
		$hid = "<span class='filter_clear'>" .  primary_filter_vis_control() . "</span>"; 
		$clr = "<span class='filter_clear'>" . filter_clear_control('primary_filter_field') . "</span>";
		$clps = make_intermediate_collapse_control();
		
		$lab = "<span class='filter_label' >Primary Filter</span>";
		$str .= "<div class='filter_caption'> $lab $clps $clr $hid </div>\n";

		// set up table to hold fields
		list($cell_s, $cell_f) = array("<td>", "</td>");		
		list($row_s, $row_f) = array("<tr>", "</tr>\n");		
		$str .= "<table class='FTab' id='primary_filter_table' >\n";
		
		foreach($primary_filter_defs as $id => $spec) {
			$data['id'] = $id;
			$data['name'] = $data['id'];
			$data['class'] = 'primary_filter_field filter_input_field';
			$data['size'] =  15;
            $data['maxlength'] = '100';
			$data['value'] = $spec["value"];
			$choosers = $CI->entry_form->make_choosers($id, $spec, " &nbsp; ", "");
			$str .= $row_s . $cell_s . $spec["label"] . $cell_f  . $cell_s . form_input($data) . $choosers . $cell_f . $row_f;
		}
		$str .= "</table>\n";
		return $str;
	}

	// --------------------------------------------------------------------
	// build HTML for table containing secondary filter fields
	//(someday) cross-check number of filters against depth of fx
	function make_secondary_filter($sec_filter_display_info)
	{
		$sfdi =& $sec_filter_display_info;

		$str = '';
		
		$hid = "<span class='filter_clear'>" .  secondary_filter_vis_control() . "</span>"; 
		$clr = "<span class='filter_clear'>" . filter_clear_control('secondary_filter_input') . "</span>";
		
		$lab = "<span class='filter_label' >Secondary Filter</span>";
		$str .= "<div class='filter_caption'> $lab $clr $hid </div>\n";

		list($cell_s, $cell_f) = array("<td>", "</td>");		
		list($row_s, $row_f) = array("<tr>", "</tr>\n");		
		$str .= "<table  class='FTab' id='secondary_filter_table' >";
		for($i=0; $i<count($sfdi); $i++) {
			$inputSpec = array(
              'name'        => 'qf_comp_val[]',
              'value'       => $sfdi[$i]->curVal,
              'maxlength'   => '80',
              'size'        => '20',
			  'class'       => 'secondary_filter_input filter_input_field',
            );
			$r = array();
			$r[] = form_dropdown('qf_rel_sel[]', $sfdi[$i]->relSelOpts, $sfdi[$i]->curRel);
			$r[] = form_dropdown('qf_col_sel[]', $sfdi[$i]->flds, $sfdi[$i]->curCol, $sfdi[$i]->js);
			$r[] = "<span id='qf_comp_sel_container_$i'>" . form_dropdown('qf_comp_sel[]', $sfdi[$i]->cmpSelOpts, $sfdi[$i]->curComp) . "</span>";
			$r[] = form_input($inputSpec);
			$str .= $row_s . $cell_s . implode($cell_f.$cell_s, $r) . $cell_f . $row_f;
		}
		$str .= "</table>\n";
		return $str;
	}

	// --------------------------------------------------------------------
	function make_sorting_filter($current_filter_values, $cols)
	{
		$str = '';
		
		$hid = "<span class='filter_clear'>" .  sorting_filter_vis_control() . "</span>"; 
		$clr = "<span class='filter_clear'>" . filter_clear_control('sorting_filter_input') . "</span>";
		
		$lab = "<span class='filter_label' >Sorting</span>";
		$str .= "<div class='filter_caption'> $lab $clr $hid </div>\n";
		
		// selection lists for column and direction selectors
		array_unshift($cols, '');
		$col_sel = array_combine($cols, $cols);
		$dir_sel = array('ASC' => 'Ascending', 'DESC' => 'Descending');
		
		// build sorting elements and put into table
		list($cell_s, $cell_f) = array("<td>", "</td>");		
		list($row_s, $row_f) = array("<tr>", "</tr>\n");		
		$str .= "<table class='FTab' id='sorting_filter_table' >";
		$i = 0;
		foreach($current_filter_values as $sort) {
			$class = 'class="sorting_filter_input filter_input_field "';
			$cid = "id=\"qf_sort_col_$i\" ";
			$did = "id=\"qf_sort_dir_$i\" ";
			$action = " onchange='\$(\"qf_sort_dir_$i\").val\(\"ASC\"\)' "; // set default value on dir when changing column
			$c = form_dropdown('qf_sort_col[]', $col_sel, $sort['qf_sort_col'], $class . $cid . $action);
			$d = form_dropdown('qf_sort_dir[]', $dir_sel, $sort['qf_sort_dir'], $did);
			$str .= $row_s . $cell_s . $c .$cell_f . $cell_s . $d . $cell_f . $row_f;
			$i++;
		}
		$str .= "</table>\n";
		return $str;
	}

	// --------------------------------------------------------------------
	function make_paging_filter($current_filter_values)
	{
		$str = '';
		foreach($current_filter_values as $name => $value) {
			$data = array(
				'id' => $name,
				'name' => $name,
				'value' => $value,
			);
			$str .= form_input($data);
		}
		return $str;
	}

	// --------------------------------------------------------------------
	function make_column_filter($cols, $col_filter, $col_filter_size = 6)
	{
		$str = "";

		$options = array();
		foreach($cols as $col) {
			if($col[0] != '#') { // do not show columns with names that begin with hash
				$options[$col] = $col;
			}
		}
		$hid = "<span class='filter_clear'>" .  column_filter_vis_control() . "</span>"; 
		$clr = "<span class='filter_clear'>" . filter_clear_control('cf_column_selection_ctl', 'gamma.clearSelector') . "</span>";
		$lab = "<span class='filter_label' >Column Filter</span>";
		$caption = "$lab $clr $hid";
		
		$str .= "<div class='filter_caption'>$caption</div>";
		$str .= "<div>";
		$str .= "<table class='FTab'><tr><td>";
		$str .= form_multiselect('cf_column_selection[]', $options, $col_filter, 'id="cf_column_selection_ctl" size="'.$col_filter_size.'" class="filter_col"');
		$str .= form_hidden('cf_column_selection_marker', 'yes');
		$str .= "</td></tr></table>";
		$str .= "</div>";
		return $str;
	}
