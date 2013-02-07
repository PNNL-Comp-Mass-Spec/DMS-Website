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
		$s = "<span class='expando_section ui-icon $icon $class'></span>";
		return $s;
	}
	// --------------------------------------------------------------------
	function label_link_icon($usage = 'plus', $class = '', $label = '') {
		$icon = get_link_image($usage);
		$label = ($label) ? "<span class='icon_link_label'>$label</span>" : "";
		$s = "$label <span class='expando_section ui-icon $icon $class'></span>";
		return $s;
	}
	// --------------------------------------------------------------------
	function detail_report_cmd_link($label, $js = '', $tooltip = '', $href = '' )
	{
		$click = ($js)? "onclick='$js' " : "" ;
		$href = ($href) ? "href='" . site_url() . "$href' " : "href='javascript:void(0)'" ;
		$title = ($tooltip) ? "title='$tooltip'" : "" ;
		return "<a class='cmd_link_a'$href$click$title >" . label_link_icon('go', '', $label) . "</a>";
	}	
	// --------------------------------------------------------------------
	function filter_section_visibility_control($label, $filter_container, $icon_type, $tool_tip)
	{
		if(!$icon_type) $icon_type = 'plus';
		$marker_class = $filter_container;
		$icon = label_link_icon($icon_type, $marker_class, $label);
		$s = "<a class='cmd_link_a' href='javascript:void(0)' onclick='lambda.toggleFilterVisibility(\"$filter_container\", 0.1, this)' title='$tool_tip'>$icon</a>";
		return $s;
	}
	// --------------------------------------------------------------------
	function column_filter_vis_control($label = '', $icon_type = 'plus')
	{
		return filter_section_visibility_control($label, 'column_filter_container', $icon_type, 'Show or hide the column filter');
	}
	function primary_filter_vis_control($label = '', $icon_type = 'plus') 
	{
		return filter_section_visibility_control($label, 'primary_filter_container', $icon_type, 'Show or hide the primary filter');
	}
	function secondary_filter_vis_control($label = '', $icon_type = 'plus') 
	{
		return filter_section_visibility_control($label, 'secondary_filter_container', $icon_type, 'Show or hide the secondary filter');
	}
 	function sorting_filter_vis_control($label = '', $icon_type = 'plus') 
	{
		return filter_section_visibility_control($label, 'sorting_filter_container', $icon_type, 'Show or hide the sorting filter');		
	}

	// --------------------------------------------------------------------
 	function filter_clear_control($container) {
 		return "<a class='cmd_link_a' href='javascript:void(0)' onclick='gamma.clearSelector(\"" . $container . "\")' >" . cmd_link_icon('close') . "</a>";
 	}

	// --------------------------------------------------------------------
	function search_btn()
	{
		return "<input class='button search_btn' type='button' onclick='lstRep.updateMyData(\"reset\")' value='Search' id='search_button' />";
	}
	function clear_filters_btn()
	{
		return "<a class='cmd_link_a' href='javascript:void(0)' onclick='lambda.clearSearchFilters()' title='Clear any existing filter values'>" . label_link_icon('delete', '', 'Clear') . "</a>";
	}
	function collapse_filters_btn() 
	{
		return "<span id='show_less_filter'><a class='cmd_link_a' href='javascript:void(0)' onclick='lstRep.updateMyFilter(\"minimal\")' title='Show only the primary filter'>". label_link_icon('minus', '', 'Minimize') . "</a></span>";
	}
	function expand_filters_btn() 
	{
		return "<span id='show_more_filter'><a class='cmd_link_a' href='javascript:void(0)' onclick='lstRep.updateMyFilter(\"maximal\")' title='Show all filters'>" . label_link_icon('plus', '', 'Expand') . "</a></span>";
	}	
	// --------------------------------------------------------------------
	function general_visibility_control($label, $containerId, $tooltip = '')
	{
		$tt = 'Show or hide section';
		if($tooltip) $tt .= ' for ' . $tooltip;
		return "<a class='cmd_link_a' title='$tt' href='javascript:void(0)' onclick='gamma.toggleVisibility(\"$containerId\", 0.5, this)'>$label " . expansion_link_icon() . "</a>";
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