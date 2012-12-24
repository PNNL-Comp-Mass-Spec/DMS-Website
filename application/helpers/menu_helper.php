<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// -----------------------------------
	function make_qs_fly_menu($params)
	{
		$num_menu_itmes = count($params['section_menu_items']);
		$s = '';
		$s .= "<div class='qs_menu fly_aspect'>\n";

		$s .= "<div class='qs_menu_active_area  fly_aspect_active_area'>\n";
		$s .= "<div class='qs_menu_hdr fly_aspect_active_area'>".$params['section_header']."</div>\n";

		if($params['section_comment']) {
			$s .= "<div class='fly_aspect_menu_comment'>\n";
			$s .= "<span>".$params['section_comment']."</span>\n";
			$s .= "</div>\n";		
		}

		if($num_menu_itmes > 0) {
			$s .= "<ul>\n";
			for($i=0; $i<$num_menu_itmes; $i++) {
				$s .= make_qs_menu_item($params, $i);
			}
			$s .= "</ul>\n";
		}
		$s .= "</div>\n";
		$s .= "</div>\n";
		return $s;
	}

	// -----------------------------------
	function make_fly_section_layout($section_defs)
	{
		$s = '';
		foreach($section_defs as $section => $params) {
//$params['section_comment']
			$s .= "<div id='$section' class='fly_box'>\n";
			$s .= make_qs_fly_menu($params);
			$s .= "</div>\n";
		}
		return $s;
	}
	
	// -----------------------------------
	function make_fly_master_list($section_defs)
	{
		$s = '';
		$s .= "<div>\n";
		foreach($section_defs as $section => $params) {
//$params['section_comment']
			$lbl = $params['section_header'];
			$s .= "<ul>\n";
//			$s .= "<li><a href='javascript:void(0)' onmouseover='showFlyMenu(\"$section\")'>$lbl</a></li>";
			$s .= "<li><a href='javascript:void(0)' onmouseover='showFlyMenuOnDelay(\"$section\")' onmouseout='cancelShowFlyMenuOnDelay()' >$lbl</a></li>";
			$s .= "</ul>\n";
		}
		$s .= "</div>\n";
		return $s;
	}

// -----------------------------------
	function make_qs_menu_item($params, $i)
	{
		$s = '';
		$page = $params['section_menu_items'][$i]['page'];
		$url = strncasecmp($page, "http", 4)? site_url().$page: $page;
		$lnk = $params['section_menu_items'][$i]['link'];
		$lbl = $params['section_menu_items'][$i]['label'];
		switch($page) {
			case 'submenu':
				$s .= "<li><span style='font-weight:bold;'>$lnk</span></li>\n";
				break;
			default:
				$s .= "<li><a href='$url'>$lnk</a> $lbl</li>\n";
				break;
		}
		return $s;
	}

// -----------------------------------
	function make_qs_section($params, $num_revealed = 2)
	{
		$num_menu_itmes = count($params['section_menu_items']);
		$num_revealed = ($num_revealed>$num_menu_itmes)?$num_menu_itmes:$num_revealed;
		$sect_name = "b".$params['section_number'];
		$s = '';
		$s .= "<div class='qs_menu qs_aspect'>\n";

		$s .= "<div class='qs_menu_active_area qs_aspect_active_area'>\n";
		$s .= "<div class='qs_menu_hdr'>".$params['section_header']."</div>\n";

		if($num_menu_itmes > 0) {
			$s .= "<ul>\n";
			for($i=0; $i<$num_revealed; $i++) {
				$s .= make_qs_menu_item($params, $i);
			}
			$s .= "</ul>\n";
		}

		$s .= "<div>\n";
		if($num_menu_itmes > $num_revealed) {
			$s .= "	<a href='javascript:void(0)' onclick='showHideMenuBlock(\"".$sect_name."\")'><span id='".$sect_name."_ctl'>More...</span></a>\n";
			$s .= "	<div id='".$sect_name."' class='qs_more'>\n";
			$s .= "	<ul>\n";
			for($i=$num_revealed; $i<$num_menu_itmes; $i++) {
				$s .= make_qs_menu_item($params, $i);
			}
			$s .= "	</ul>\n";
			$s .= "</div>\n";
		}
		$s .= "</div>\n";
		$s .= "</div>\n";

		$s .= "<div class='qs_menu_comment qs_aspect_comment'>\n";
		$s .= "<span>".$params['section_comment']."</span>\n";
		$s .= "</div>\n";

		$s .= "</div>\n";
		return $s;
	}

	// -----------------------------------
	// lay out sections in grid
	function make_qs_layout($section_defs)
	{
		$sections = array_keys($section_defs);
		$num_sections = count($sections);
		$num_layout_columns = 2;
		$s = '';
		$s .= "<table class='qs_layout'>\n";

		$grid_row = 0;
		$done = false;
		while (!$done) {
			$rs = '';
			for($grid_col=0; $grid_col<$num_layout_columns; $grid_col++) {
				$section_num = ($grid_row * $num_layout_columns) + $grid_col;
				if($section_num < $num_sections) {
					$rs .= "<td>".make_qs_section($section_defs[$sections[$section_num]])."</td>\n";
				} else {
					$done = true;
				}
			}
			if($rs) {
				$s .= "<tr>\n$rs\n</tr>\n";
			}
			$grid_row++;
		}		
		$s .= "</table>\n";
		return $s;
	}

	// --------------------------------------------------------------------
	function side_menu_layout($menu_items, $mnu_name, $mnu_label)
	{
		if($mnu_name != '') {
			echo "<li><span class='submenu'>$mnu_label</span>\n";
			echo "<ul>\n";
		}
		foreach($menu_items as $entry) {
			if($entry['owner_menu'] == $mnu_name) {
				$name = $entry['item_name'];
				$label = $entry['item_label'];
				switch($entry['item_type']) {
					case 'submenu':
						side_menu_layout($menu_items, $name, $label);
						break;
					case 'link':
						$url = site_url().$name;
						echo "<li><a target='display_side' href='$url'>$label</a></li>\n";
						break;
					case 'separator':
						echo "<hr width='40%' align='left'>\n";
						break;
				}
			}
		}
		if($mnu_name != '') {
			echo "</ul>\n";
			echo "</li> <!-- end submenu '$mnu_label' -->\n";
		}
	}

	// --------------------------------------------------------------------
	function nav_bar_layout($menu_items, &$index = 0, $mnu_name = '', $mnu_label = '')
	{
		if($mnu_name != '') {
			echo "<li><span><a onClick='expose_menu(\"ddm_$index\")' href='javascript:void(0);'>$mnu_label</a></span>\n";
			echo "<ul id='ddm_$index' class='ddm'>\n";
		}
		foreach($menu_items as $entry) {
			if($entry['owner_menu'] == $mnu_name) {
				$name = $entry['item_name'];
				$label = $entry['item_label'];
				switch($entry['item_type']) {
					case 'submenu':
						$index++;
						nav_bar_layout($menu_items, $index, $name, $label);
						break;
					case 'link':
						$target = '';
						$url = $name;
						$click = '';
						if(!(stripos($name, 'javascript') === FALSE)) {
							$click = "onclick='" . str_replace('javascript:', '', $name) . "'";
							$url = "javascript:void(0)";
							$target = '';
						}
						elseif(!(stripos($name, 'http') === FALSE)) {
							$target = "target='_blank'";
						} 
						else {
							$url = site_url().$url;
						}
						$help = $entry['item_help'];
						$title = ($help)?"title='$help'":'';
						echo "<li><a $title href='$url' $click $target>$label</a></li>\n";
						break;
					case 'separator':
						echo "<li class=mnusep >$label</li>\n";
						break;
					case 'header':
						echo "<li>$label</li>\n";
						break;
					default:
						// don't output anything if the link type is not recognized
						// (unconverted context items are hidden by this mechanism)
						break;
				}
			}
		}
		if($mnu_name != '') {
			echo "</ul>\n";
			echo "</li> <!-- end submenu '$mnu_label' -->\n";
		}
	}

	// --------------------------------------------------------------------
	// 
	function make_version_banner()
	{	
		$s = '';
		$CI =& get_instance();
		$banner = $CI->config->item('version_banner');
		$color = $CI->config->item('version_color_code');
		if($banner) {
			$s .= "<span style='color:$color;font-weight:bold;'>$banner</span>";
			$s = implode(implode(" ", array_fill(0, 5, "&nbsp;")), array_fill(0, 6, $s));
		}
		return $s;
	}

	// --------------------------------------------------------------------
	// get page-specific information necessary for drop-down menu bar
	function set_up_nav_bar($page_type)
	{
		$CI =& get_instance();
		$CI->help_page_link = $CI->config->item('pwiki') . $CI->config->item('wikiHelpLinkPrefix');
		$CI->load->helper(array('dms_search'));
		$CI->load->model('dms_menu', 'menu', TRUE);
		return get_nav_bar_menu_items($page_type);
	}
	
	
	// --------------------------------------------------------------------
	// get the definition of the nav_bar menu and roll in the context-sensitive stuff
	function get_nav_bar_menu_items($page_type)
	{	
		$CI =& get_instance();
		$menu_context = get_menu_context($page_type);
		$nav_bar_menu_items = $CI->menu->get_menu_def("dms_menu.db", "nav_def");
		convert_context_sensitive_menu_items($nav_bar_menu_items, $menu_context);	
		return $nav_bar_menu_items;
	}


	// --------------------------------------------------------------------
	function get_menu_context($page_type)
	{
		// we get context sensitive values from controller
		$CI =& get_instance();

		// get array of context-sensitive values
		$menu_context = array();
		if(isset($CI->help_page_link)) {
			$help_basic_link = $CI->help_page_link.$page_type;
			$menu_context['help_basic_link'] = $help_basic_link;
			if(isset($CI->my_tag)) {	
				$menu_context['help_page_link'] = $CI->help_page_link.$CI->my_tag;
			}
		}
		if(isset($CI->my_tag)) {			
			switch($page_type) {
				case 'List_Reports':
				case 'Param_Pages':
					$menu_context['clear_settings_link'] = "javascript:kappa.setListReportDefaults(\"".site_url().$CI->my_tag."/defaults/$page_type\")";
					break;
			}		
			$config_db = (isset($CI->my_config_db))?$CI->my_config_db:$CI->my_tag;
			$menu_context['config_db_link'] = "config_db/show_db/".$config_db.".db";
		}
		switch($page_type) {
			case 'Entry_Pages'  :
				break;
			case 'List_Reports':
				$menu_context['sql_link'] = "javascript:updateShowSQL()";
				break;
			case 'Detail_Reports' :
				$menu_context['sql_link'] = "javascript:updateShowSQL()";
				break;
			case 'Param_Pages':
				break;
		}		
		$version = $CI->config->item('version_label');
		$color_code = $CI->config->item('version_color_code');
		$menu_context['side_panel_toggle'] = "<span style='margin:0;'><a title='Show/Hide side menu' href='javascript:gamma.toggle_frames();'><img src='".base_url()."/images/layout.png' style='border-style:none'></a></span>";
		$menu_context['server_info'] = "<span style='font-size:9px;color:".$color_code."'>".get_user()." &nbsp; &nbsp; ".$version."</span>";	
		$menu_context['home_link'] = "<span style='margin:0 0 0 5px;'><a title='Go to home page' href='".site_url()."gen/welcome'><img src='".base_url()."/images/house.png' style='border-style:none'></a></span>";
		$menu_context['admin_page_link'] = "<span style='margin:0 0 0 5px;'><a title='Go to admin menu page' href='".site_url()."gen/admin'><img src='".base_url()."/images/cog.png' style='border-style:none'></a></span>";

		$menu_context['user_notification_link'] = "notification/report/-/".get_user();
		$menu_context['email_notification_link'] = "notification/edit/".get_user();

		return $menu_context;
	}

	// --------------------------------------------------------------------
	// convert context-sensitive menu items in the input menu item def array
	// into simple links that are processable by nav_bar_layout
	function convert_context_sensitive_menu_items(&$menu_items, $context)
	{
		for($i = 0; $i < count($menu_items); $i++) {
			switch($menu_items[$i]['item_type']) {
				case 'link_context':
					$name = $menu_items[$i]['item_name'];
					if(array_key_exists($name, $context)) {
						$menu_items[$i]['item_type'] = 'link';
						$menu_items[$i]['item_name'] = $context[$name];
					}
					break;
				case 'header_context':
					$name = $menu_items[$i]['item_name'];
					if(array_key_exists($name, $context)) {
						$menu_items[$i]['item_type'] = 'header';
						$menu_items[$i]['item_label'] = $context[$name];
					}
					break;
			}
		}
	}

?>
