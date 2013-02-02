<?php

	// --------------------------------------------------------------------
	function cmd_link_icon($usage = 'cmd') {
		$icon = get_link_image($usage);
		$s = "<span class='expando_section ui-icon $icon'></span>";
		return $s;
	}

	// --------------------------------------------------------------------
	function expansion_link_icon($usage = 'plus', $class = '') {
		$icon = get_link_image($usage);
		$s = "<span class='expando_section ui-icon $icon'></span>";
		return $s;
	}
	
	// --------------------------------------------------------------------
	function filter_section_visibility_control($label, $filter_container, $icon_type, $tool_tip)
	{
		if(!$icon_type) $icon_type = 'plus';
		$marker_class = $filter_container;
		$icon = expansion_link_icon($icon_type, $marker_class);
		$s = "$label <a href='javascript:void(0)' onclick='lambda.sectionToggle(\"$filter_container\", 0.1, this)' title='$tool_tip'>$icon</a>";
		return $s;
	}
	// --------------------------------------------------------------------
	function column_filter_vis_control($label, $icon_type = 'plus')
	{
		return filter_section_visibility_control($label, 'column_filter_container', $icon_type, 'Show or hide the column filter');
	}
	function primary_filter_vis_control($label, $icon_type = 'plus') 
	{
		return filter_section_visibility_control($label, 'primary_filter_container', $icon_type, 'Show or hide the primary filter');
	}
	function secondary_filter_vis_control($label, $icon_type = 'plus') 
	{
		return filter_section_visibility_control($label, 'secondary_filter_container', $icon_type, 'Show or hide the secondary filter');
	}
 	function sorting_filter_vis_control($label, $icon_type = 'plus') 
	{
		return filter_section_visibility_control($label, 'sorting_filter_container', $icon_type, 'Show or hide the sorting filter');		
	}
 
	// --------------------------------------------------------------------
	function get_link_image($usage) {
		$s = "";
		switch($usage) {
			case "go":
				$s = "ui-icon-circle-arrow-e";
				break;
			case "down":
				$s = "ui-icon-circle-arrow-s";
				break;
			case "minus":
				$s = "ui-icon-circle-minus";
				break;
			case "plus":
				$s = "ui-icon-circle-plus";
				break;
			case "cmd":
				$s = "ui-icon-circle-triangle-e";
				break;
			case "close":
			case "delete":
				$s = "ui-icon-closethick";
				break;
			case "refresh":
				$s = "ui-icon-transferthick-e-w"; // ui-icon-refresh
				break;
			case "last":
				$s = "ui-icon-seek-end";
				break;
			case "first":
				$s = "ui-icon-seek-first";
				break;
			case "next":
				$s = "ui-icon-seek-next";
				break;
			case "prev":
				$s = "ui-icon-seek-prev";
				break;
			case "adjust":
				$s = "ui-icon-wrench";
				break;
			default:
				$s = '??';
				break;
		}
		return $s;	
	}
?>