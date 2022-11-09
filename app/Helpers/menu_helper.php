<?php

/**
 * Make a menu that dynamically appears on the welcome page (https://dms2.pnl.gov/gen/welcome)
 * @param type $params
 * @return string
 */
function make_qs_fly_menu($params) {
    $num_menu_items = count($params['section_menu_items']);
    $s = '';
    $s .= "<div class='qs_menu fly_aspect'>\n";

    $s .= "<div class='qs_menu_active_area  fly_aspect_active_area'>\n";
    $s .= "<div class='qs_menu_hdr fly_aspect_active_area'>" . $params['section_header'] . "</div>\n";

    if ($params['section_comment']) {
        $s .= "<div class='fly_aspect_menu_comment'>\n";
        $s .= "<span>" . $params['section_comment'] . "</span>\n";
        $s .= "</div>\n";
    }

    if ($num_menu_items > 0) {
        $s .= "<ul>\n";
        for ($i = 0; $i < $num_menu_items; $i++) {
            $s .= make_qs_menu_item($params, $i);
        }
        $s .= "</ul>\n";
    }
    $s .= "</div>\n";
    $s .= "</div>\n";
    return $s;
}

/**
 * Generate menus that dynamically appear on the welcome page (https://dms2.pnl.gov/gen/welcome)
 * @param type $section_defs
 * @return string
 */
function make_fly_section_layout($section_defs) {
    $s = '';
    foreach ($section_defs as $section => $params) {
//$params['section_comment']
        $s .= "<div id='$section' class='fly_box'>\n";
        $s .= make_qs_fly_menu($params);
        $s .= "</div>\n";
    }
    return $s;
}

/**
 * Make the list of menus that dynamically appear on the welcome page (https://dms2.pnl.gov/gen/welcome)
 * @param type $section_defs
 * @return string
 */
function make_fly_master_list($section_defs) {
    $s = '';
    $s .= "<div>\n";
    foreach ($section_defs as $section => $params) {
//$params['section_comment']
        $lbl = $params['section_header'];
        $s .= "<ul>\n";
//          $s .= "<li><a href='javascript:void(0)' onmouseover='showFlyMenu(\"$section\")'>$lbl</a></li>";
        $s .= "<li><a href='javascript:void(0)' onmouseover='showFlyMenuOnDelay(\"$section\")' onmouseout='cancelShowFlyMenuOnDelay()' >$lbl</a></li>";
        $s .= "</ul>\n";
    }
    $s .= "</div>\n";
    return $s;
}

/**
 * Append a dynamic menu item
 * @param type $params
 * @param type $i
 * @return type
 */
function make_qs_menu_item($params, $i) {
    $s = '';
    $page = $params['section_menu_items'][$i]['page'];
    $url = strncasecmp($page, "http", 4) ? site_url($page) : $page;
    $lnk = $params['section_menu_items'][$i]['link'];
    $lbl = $params['section_menu_items'][$i]['label'];
    switch ($page) {
        case 'submenu':
            $s .= "<li><span style='font-weight:bold;'>$lnk</span></li>\n";
            break;
        default:
            $s .= "<li><a href='$url'>$lnk</a> $lbl</li>\n";
            break;
    }
    return $s;
}

/**
 * Append a dynamic menu section
 * @param type $params
 * @param type $default_num_revealed
 * @return string
 */
function make_qs_section($params, $default_num_revealed = 2) {
    $num_menu_items = count($params['section_menu_items']);
    $num_revealed = ($default_num_revealed > $num_menu_items) ? $num_menu_items : $default_num_revealed;
    $sect_name = "b" . $params['section_number'];
    $s = '';
    $s .= "<div class='qs_menu qs_aspect'>\n";

    $s .= "<div class='qs_menu_active_area qs_aspect_active_area'>\n";
    $s .= "<div class='qs_menu_hdr'>" . $params['section_header'] . "</div>\n";

    if ($num_menu_items > 0) {
        $s .= "<ul>\n";
        for ($i = 0; $i < $num_revealed; $i++) {
            $s .= make_qs_menu_item($params, $i);
        }
        $s .= "</ul>\n";
    }

    $s .= "<div>\n";
    if ($num_menu_items > $num_revealed) {
        $s .= " <a href='javascript:void(0)' onclick='showHideMenuBlock(\"" . $sect_name . "\")'><span id='" . $sect_name . "_ctl'>More...</span></a>\n";
        $s .= " <div id='" . $sect_name . "' class='qs_more'>\n";
        $s .= " <ul>\n";
        for ($i = $num_revealed; $i < $num_menu_items; $i++) {
            $s .= make_qs_menu_item($params, $i);
        }
        $s .= " </ul>\n";
        $s .= "</div>\n";
    }
    $s .= "</div>\n";
    $s .= "</div>\n";

    $s .= "<div class='qs_menu_comment qs_aspect_comment'>\n";
    $s .= "<span>" . $params['section_comment'] . "</span>\n";
    $s .= "</div>\n";

    $s .= "</div>\n";
    return $s;
}

/**
 * Layout sections in grid
 * @param type $section_defs
 * @return string
 */
function make_qs_layout($section_defs) {
    $sections = array_keys($section_defs);
    $num_sections = count($sections);
    $num_layout_columns = 2;
    $s = '';
    $s .= "<table class='qs_layout'>\n";

    $grid_row = 0;
    $done = false;
    while (!$done) {
        $rs = '';
        for ($grid_col = 0; $grid_col < $num_layout_columns; $grid_col++) {
            $section_num = ($grid_row * $num_layout_columns) + $grid_col;
            if ($section_num < $num_sections) {
                $rs .= "<td>" . make_qs_section($section_defs[$sections[$section_num]]) . "</td>\n";
            } else {
                $done = true;
            }
        }
        if ($rs) {
            $s .= "<tr>\n$rs\n</tr>\n";
        }
        $grid_row++;
    }
    $s .= "</table>\n";
    return $s;
}

/**
 * Build the side menu object tree
 * @param type $menu_items
 * @param type $mnu_name
 * @return \stdClass
 */
function build_side_menu_object_tree($menu_items, $mnu_name) {
    $items = array();
    foreach ($menu_items as $entry) {
        if ($entry['owner_menu'] == $mnu_name) {
            $name = $entry['item_name'];
            $label = $entry['item_label'];
            switch ($entry['item_type']) {
                case 'submenu':
                    $obj = new \stdClass();
                    $obj->title = $label;
                    $obj->folder = true;
                    $obj->children = build_side_menu_object_tree($menu_items, $name);
                    $items[] = $obj;
                    break;
                case 'link':
                    $obj = new \stdClass();
                    $obj->title = $label;
                    $obj->href = site_url($name);
                    $items[] = $obj;
                    break;
                case 'url_link':
                    $obj = new \stdClass();
                    $obj->title = $label;
                    $obj->href = $name;
                    $items[] = $obj;
                    break;
            }
        }
    }
    return $items;
}

/**
 * Construct the navigation bar
 * @param type $menu_items
 * @param type $index
 * @param type $mnu_name
 * @param type $mnu_label
 */
function nav_bar_layout($menu_items, &$index = 0, $mnu_name = '', $mnu_label = '') {
    if ($mnu_name != '') {
        echo "<li><span><a onClick='navBar.expose_menu(\"ddm_$index\")' href='javascript:void(0);'>$mnu_label</a></span>\n";
        echo "<ul id='ddm_$index' class='ddm'>\n";
    }
    foreach ($menu_items as $entry) {
        if ($entry['owner_menu'] == $mnu_name) {
            $name = $entry['item_name'];
            $label = $entry['item_label'];
            switch ($entry['item_type']) {
                case 'submenu':
                    $index++;
                    nav_bar_layout($menu_items, $index, $name, $label);
                    break;
                case 'link':
                    $target = '';
                    $url = $name;
                    $click = '';
                    if (!(stripos($name, 'javascript') === false)) {
                        $click = "onclick='" . str_replace('javascript:', '', $name) . "'";
                        $url = "javascript:void(0)";
                        $target = '';
                    } elseif (!(stripos($name, 'http') === false)) {
                        $target = "target='_blank'";
                    } else {
                        $url = site_url($url);
                    }
                    $help = $entry['item_help'];
                    $title = ($help) ? "title='$help'" : '';
                    echo "<li><a class='mnuitem' $title href='$url' $click $target>$label</a></li>\n";
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
    if ($mnu_name != '') {
        echo "</ul>\n";
        echo "</li> <!-- end submenu '$mnu_label' -->\n";
    }
}

/**
 * Make the version banner
 * @return type
 */
function make_version_banner() {
    $s = '';
    $banner = config('App')->version_banner;
    $color = config('App')->version_color_code;
    if ($banner) {
        $s .= "<span style='color:$color;font-weight:bold;'>$banner</span>";
        $s = implode(implode(" ", array_fill(0, 5, "&nbsp;")), array_fill(0, 3, $s));
    }
    return $s;
}

/**
 * Get page-specific information necessary for drop-down menu bar
 * @param type $page_type
 * @return type
 */
function set_up_nav_bar($page_type, $controller) {
    $controller->help_page_link = config('App')->pwiki . config('App')->wikiHelpLinkPrefix;
    helper(['dms_search']);
    $controller->menu = model('\\App\\Models\\Dms_menu');
    return get_nav_bar_menu_items($page_type, $controller);
}

/**
 * Get the definition of the nav_bar menu and roll in the context-sensitive stuff
 * @param type $page_type
 * @return type
 */
function get_nav_bar_menu_items($page_type, $controller) {
    $menu_context = get_menu_context($page_type, $controller);
    $nav_bar_menu_items = $controller->menu->get_menu_def("dms_menu.db", "nav_def");
    convert_context_sensitive_menu_items($nav_bar_menu_items, $menu_context);
    return $nav_bar_menu_items;
}

/**
 * Get menu context
 * @param type $page_type
 * @return string
 */
function get_menu_context($page_type, $controller) {
    // we get context sensitive values from controller

    // get array of context-sensitive values
    $menu_context = array();
    if (isset($controller->help_page_link)) {
        $help_basic_link = $controller->help_page_link . $page_type;
        $menu_context['help_basic_link'] = $help_basic_link;
        if (isset($controller->my_tag)) {
            $menu_context['help_page_link'] = $controller->help_page_link . $controller->my_tag;
        }
    }
    if (isset($controller->my_tag)) {
        switch ($page_type) {
            case 'List_Reports':
            case 'Param_Pages':
                $menu_context['clear_settings_link'] = "javascript:navBar.invoke(lambda.setListReportDefaults, \"$page_type\")";
                break;
        }
        $config_db = (isset($controller->my_config_db)) ? $controller->my_config_db : $controller->my_tag;
        $menu_context['config_db_link'] = "config_db/show_db/" . $config_db . ".db";
    }
    switch ($page_type) {
        case 'Entry_Pages' :
            break;
        case 'List_Reports':
            // Call function updateMessageBox in dms2.js to obtain the SQL or the URL behind the given list report
            // That function POSTs a request to a report_info/url or report_info/sql page
            // For example http://dms2.pnl.gov/dataset_qc/report_info/url
            //          or http://dms2.pnl.gov/dataset_qc/report_info/sql
            $menu_context['sql_link'] = "javascript:navBar.invoke(gamma.pageContext.updateShowSQL)";
            $menu_context['url_link'] = "javascript:navBar.invoke(gamma.pageContext.updateShowURL)";
            break;
        case 'Detail_Reports' :
            // Call function updateMessageBox in dms2.js to obtain the SQL behind the given detail report
            $menu_context['sql_link'] = "javascript:navBar.invoke(gamma.pageContext.updateShowSQL)";
            $menu_context['url_link'] = "javascript:navBar.invoke(gamma.pageContext.updateShowURL)";
            break;
        case 'Param_Pages':
            // Call function updateMessageBox in dms2.js to obtain the URL behind the given param report
            // That function POSTs a request to a param_info/url or param_info/sql page
            // For example http://dms2.pnl.gov/requested_run_batch_blocking/param_info/url
            $menu_context['url_link'] = "javascript:navBar.invoke(gamma.pageContext.updateShowURL)";
            break;
    }
    $version = config('App')->version_label;
    $color_code = config('App')->version_color_code;
    $menu_context['side_panel_toggle'] = "<span style='margin:0;'><a title='Show/Hide side menu' href='javascript:gamma.toggle_frames();'><img src='" . base_url('/images/layout.png') . "' style='border-style:none'></a></span>";
    $menu_context['server_info'] = "<span style='font-size:9px;color:" . $color_code . "'>" . get_user() . " &nbsp; &nbsp; " . $version . "</span>";
    $menu_context['home_link'] = "<span style='margin:0 0 0 5px;'><a title='Go to home page' href='" . site_url("gen/welcome") . "'><img src='" . base_url('/images/house.png') . "' style='border-style:none'></a></span>";
    $menu_context['admin_page_link'] = "<span style='margin:0 0 0 5px;'><a title='Go to admin menu page' href='" . site_url("gen/admin") . "'><img src='" . base_url('/images/cog.png') . "' style='border-style:none'></a></span>";

    $menu_context['user_notification_link'] = "notification/report/-/" . get_user();
    $menu_context['email_notification_link'] = "notification/edit/" . get_user();

    return $menu_context;
}

/**
 * Convert context-sensitive menu items in the input menu item def array
 * into simple links that are processable by nav_bar_layout
 * @param type $menu_items
 * @param type $context
 */
function convert_context_sensitive_menu_items(&$menu_items, $context) {
    for ($i = 0; $i < count($menu_items); $i++) {
        switch ($menu_items[$i]['item_type']) {
            case 'link_context':
                $name = $menu_items[$i]['item_name'];
                if (array_key_exists($name, $context)) {
                    $menu_items[$i]['item_type'] = 'link';
                    $menu_items[$i]['item_name'] = $context[$name];
                }
                break;
            case 'header_context':
                $name = $menu_items[$i]['item_name'];
                if (array_key_exists($name, $context)) {
                    $menu_items[$i]['item_type'] = 'header';
                    $menu_items[$i]['item_label'] = $context[$name];
                }
                break;
        }
    }
}
