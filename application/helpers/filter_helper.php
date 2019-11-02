<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Make minimal search filter
 * @param type $cols
 * @param type $current_paging_filter_values
 * @param type $current_primary_filter_values
 * @param type $sec_filter_display_info
 * @param type $current_sorting_filter_values
 * @param type $col_filter
 */
function make_search_filter_minimal($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter) {
    $g = make_paging_filter($current_paging_filter_values);
    $p = make_primary_filter($current_primary_filter_values);
    $s = make_secondary_filter($sec_filter_display_info);
    $r = make_sorting_filter($current_sorting_filter_values, $cols);

    $col_filter_size = 5;
    $c = make_column_filter($cols, $col_filter, $col_filter_size);

    $style = 'display:none;float:left;padding:3px 3px 0 0;';


    $pageDiv = "<div style='display:none' > $g </div>";
    $primaryDiv = "<div id='primary_filter_container' class='filter_container_box' class='filter_container_box' style='clear:both;' > $p </div>";
    $secondaryDiv = "<div id='secondary_filter_container' class='filter_container_box' style='$style' > $s </div>";
    $sortDiv = "<div id='sorting_filter_container' class='filter_container_box' style='$style' > $r </div>";
    $colFilterDiv = "<div id='column_filter_container' class='filter_container_box' style='$style' > $c </div>";

    echo $pageDiv;
    echo "<div style='height:3px;' ></div>";
    echo $primaryDiv;
    echo $secondaryDiv;
    echo $sortDiv;
    echo $colFilterDiv;
}

/**
 * Make expanded search filter
 * @param type $cols
 * @param type $current_paging_filter_values
 * @param type $current_primary_filter_values
 * @param type $sec_filter_display_info
 * @param type $current_sorting_filter_values
 * @param type $col_filter
 * @param type $filter_display_mode
 */
function make_search_filter_expanded($cols, $current_paging_filter_values, $current_primary_filter_values, $sec_filter_display_info, $current_sorting_filter_values, $col_filter, $filter_display_mode = "") {
    $big_primary_filter = big_primary_filter($current_primary_filter_values);
//      $col_filter_size = ($big_primary_filter)?14:7;
    $default_col_filter_size = 5;
    $col_filter_size = (count($cols) < $default_col_filter_size) ? count($cols) : $default_col_filter_size;

    $g = make_paging_filter($current_paging_filter_values);
    $p = make_primary_filter_in_table($current_primary_filter_values);
    $s = make_secondary_filter($sec_filter_display_info);
    $r = make_sorting_filter($current_sorting_filter_values, $cols);
    $c = make_column_filter($cols, $col_filter, $col_filter_size);

    $style = ($filter_display_mode) ? "style='display:none;'" : "";

    $pageDiv = "<div style='display:none' > $g </div>";
    $primaryDiv = "<div id='primary_filter_container' class='filter_container_box' > $p </div>";
    $secondaryDiv = "<div id='secondary_filter_container' class='filter_container_box' $style > $s </div>";
    $sortDiv = "<div id='sorting_filter_container' class='filter_container_box' $style > $r </div>";
    $colFilterDiv = "<div id='column_filter_container' class='filter_container_box' $style > $c </div>";

    // set up table to hold fields
    list($cell_s, $cell_vs, $cell_cs, $cell_f) = array(
        '<td style="vertical-align:top;">',
        '<td style="vertical-align:top;" rowspan="2">',
        '<td style="vertical-align:top;" colspan="2">',
        "</td>\n",
    );
    list($row_s, $row_f) = array("<tr>", "</tr>\n");

    $str = "<table id='search_filter_table' >\n";
    if ($big_primary_filter) {
        $str .= $row_s;
        $str .= $cell_vs . $primaryDiv . $cell_f;
        $str .= $cell_cs . $secondaryDiv . $cell_f;
        $str .= $row_f;
        $str .= $row_s;
        $str .= $cell_s . $sortDiv . $cell_f;
        $str .= $cell_s . $colFilterDiv . $cell_f;
        $str .= $row_f;
    } else {
        $str .= $row_s;
        $str .= $cell_s . $primaryDiv . $cell_f;
        $str .= $cell_s . $secondaryDiv . $cell_f;
        $str .= $cell_s . $sortDiv . $cell_f;
        $str .= $cell_s . $colFilterDiv . $cell_f;
        $str .= $row_f;
    }
    $str .= "</table>\n";

    echo $pageDiv;
    echo $str;
}

/**
 * Make filter for param reports (stored procedure based list reports)
 * Example usage: predefined_analysis_preview/param
 * @param type $cols
 * @param type $current_paging_filter_values
 * @param type $current_sorting_filter_values
 * @param type $col_filter
 */
function make_param_filter($cols, $current_paging_filter_values, $current_sorting_filter_values, $col_filter) {
    $style = 'float:left;padding:3px 3px 0 0;display:none;';

    $g = make_paging_filter($current_paging_filter_values);
    $pageDiv = "<div style='display:none' > $g </div>";
    $sortDiv = 'x';
    $colFilterDiv = '';
    if (!empty($cols)) {
        $r = make_sorting_filter($current_sorting_filter_values, $cols);
        $col_filter_size = 6;
        $c = make_column_filter($cols, $col_filter, $col_filter_size);
        $sortDiv = "<div id='sorting_filter_container' class='filter_container_box' style='$style' > $r </div>";
        $colFilterDiv = "<div id='column_filter_container' class='filter_container_box' style='$style' > $c </div>";
    }

    echo $pageDiv;
    if (!empty($cols)) {
        echo "<div style='height:3px;clear:both;' ></div>";
        echo $sortDiv;
        echo $colFilterDiv;
    }
}

/**
 * Construct the big primary filter table
 * @param type $current_primary_filter_values
 * @return boolean
 */
function big_primary_filter($current_primary_filter_values) {
    if (count($current_primary_filter_values) > 5) {
        return TRUE;
    }

    $big = FALSE;
    foreach ($current_primary_filter_values as $id => $spec) {
        if (array_key_exists("chooser_list", $spec)) {
            $big = TRUE;
            break;
        }
    }
    return $big;
}

/**
 * Intermediate expansion control
 * @return string
 */
function make_intermediate_expansion_control() {
    return '<a class="cmd_link_a" href="javascript:void(0)" onclick="lstRep.updateMyFilter(\'intermediate\')" title="Expand showing only the primary filter"><span class="expando_section ui-icon ui-icon-circle-zoomout "></span></a>';
}

/**
 * Intermediate collapse control
 * @return string
 */
function make_intermediate_collapse_control() {
    return '<a class="cmd_link_a" href="javascript:void(0)" onclick="lstRep.updateMyFilter(\'minimal\')" title="Minimize filters"><span class="expando_section ui-icon ui-icon-circle-zoomin "></span></a>';
}

/**
 * Primary filter form fields
 * @param type $primary_filter_defs
 * @return type
 */
function make_primary_filter($primary_filter_defs) {
    // get CI instance
    $CI = & get_instance();
    $CI->load->helper('form');

    $str = "";

    // Using a padding of 1 px on the bottom
    list($row_s, $row_f) = array('<div class="primary_filter_inline">', "</div>\n");
    list($cell_s, $cell_f) = array("<label>", "</label>");

    foreach ($primary_filter_defs as $id => $spec) {
        $data['id'] = $id;
        $data['name'] = $data['id'];
        $data['class'] = 'primary_filter_field filter_input_field';

        // Typically the primary filter field size defined in the model config DB is ignored and a default of 10 is used
        // However, if it ends with an exclamation mark, the given field size is used
        if (array_key_exists('size', $spec)) {
            $fieldSize = $spec["size"];
        } else {
            $fieldSize = '';
        }

        $sizeTextLength = strlen($fieldSize);
        if ($sizeTextLength > 1 && $fieldSize[$sizeTextLength - 1] === "!") {
            // Override the textbox shown with a collapsed primary filter
            $data['size'] = substr($fieldSize, 0, $sizeTextLength - 1);
        } else {
            // By default, use a textbox of size 10
            $data['size'] = 10;
        }

        if (array_key_exists('maxlength', $spec)) {
            $maxLength = $spec["maxlength"];
        } else {
            $maxLength = '';
        }

        if (!empty($maxLength) && $maxLength < 100) {
            $data['maxlength'] = $maxLength;
        } else {
            $data['maxlength'] = '100';
        }

        $data['value'] = $spec["value"];

        // The appended text will be something like this:
        //  <div class="primary_filter_inline"><label>Dataset
        //  <input type="text" name="pf_dataset" value="" id="pf_dataset" class="primary_filter_field filter_input_field" size="30" maxlength="100">
        //  </label></div>
        $str .= $row_s . $cell_s . str_replace(" ", "&nbsp;", $spec["label"]) . " " . form_input($data) . $cell_f . $row_f;
    }
    $str .= make_intermediate_expansion_control();
    return $str;
}

/**
 * Construct the primary filter table
 * @param type $primary_filter_defs
 * @return string
 */
function make_primary_filter_in_table($primary_filter_defs) {
    // get CI instance
    $CI = & get_instance();
    $CI->load->helper('form');
    $CI->load->library('entry_form');
    $CI->load->model('dms_chooser', 'choosers');

    $str = '';

    $hid = "<span class='filter_clear'>" . primary_filter_vis_control() . "</span>";
    $clr = "<span class='filter_clear'>" . filter_clear_control('primary_filter_field') . "</span>";
    $clps = make_intermediate_collapse_control();

    $lab = "<span class='filter_label' >Primary Filter</span>";
    $str .= "<div class='filter_caption'> $lab $clps $clr $hid </div>\n";

    // set up table to hold fields
    list($cell_s, $cell_f) = array("<td>", "</td>");
    list($row_s, $row_f) = array("<tr>", "</tr>\n");
    $str .= "<table class='FTab' id='primary_filter_table' >\n";

    $defaultTextboxSize = 15;

    foreach ($primary_filter_defs as $id => $spec) {
        $data['id'] = $id;
        $data['name'] = $data['id'];
        $data['class'] = 'primary_filter_field filter_input_field';

        // Typically the primary filter field size defined in the model config DB is ignored
        // However, if it ends with an exclamation mark, and if it is greater than 15, the given field size is used
        $fieldSize = $spec["size"];
        $sizeTextLength = strlen($fieldSize);
        if ($sizeTextLength > 1 && $fieldSize[$sizeTextLength - 1] === "!") {
            $fieldSize = substr($fieldSize, 0, $sizeTextLength - 1);
            if ($fieldSize > $defaultTextboxSize) {
                $data['size'] = $fieldSize;
            } else {
                // Do not shrink the textbox size below 15
                $data['size'] = $defaultTextboxSize;
            }
        } else {
            // By default, use a textbox of size 15
            $data['size'] = $defaultTextboxSize;
        }

        $maxLength = $spec["maxlength"];
        if (!empty($maxLength) && $maxLength < 100) {
            $data['maxlength'] = $maxLength;
        } else {
            $data['maxlength'] = '100';
        }

        $data['value'] = $spec["value"];
        $choosers = $CI->entry_form->make_choosers($id, $spec, " &nbsp; ", "");
        $str .= $row_s . $cell_s . $spec["label"] . $cell_f . $cell_s . form_input($data) . $choosers . $cell_f . $row_f;
    }
    $str .= "</table>\n";
    return $str;
}

/**
 * Construct the secondary filter table
 * (someday) cross-check number of filters against depth of fx
 * @param type $sec_filter_display_info
 * @return string
 */
function make_secondary_filter($sec_filter_display_info) {
    $sfdi = & $sec_filter_display_info;

    $str = '';

    $hid = "<span class='filter_clear'>" . secondary_filter_vis_control() . "</span>";
    $clr = "<span class='filter_clear'>" . filter_clear_control('secondary_filter_input') . "</span>";

    $lab = "<span class='filter_label' >Secondary Filter</span>";
    $str .= "<div class='filter_caption'> $lab $clr $hid </div>\n";

    list($cell_s, $cell_f) = array("<td>", "</td>");
    list($row_s, $row_f) = array("<tr>", "</tr>\n");
    $str .= "<table  class='FTab' id='secondary_filter_table' >";
    for ($i = 0; $i < count($sfdi); $i++) {
        $inputSpec = array(
            'name' => 'qf_comp_val[]',
            'value' => $sfdi[$i]->curVal,
            'maxlength' => '80',
            'size' => '20',
            'class' => 'secondary_filter_input filter_input_field',
        );
        $r = array();
        $r[] = form_dropdown('qf_rel_sel[]', $sfdi[$i]->relSelOpts, $sfdi[$i]->curRel);
        $r[] = form_dropdown('qf_col_sel[]', $sfdi[$i]->flds, $sfdi[$i]->curCol, $sfdi[$i]->js);
        $r[] = "<span id='qf_comp_sel_container_$i'>" . form_dropdown('qf_comp_sel[]', $sfdi[$i]->cmpSelOpts, $sfdi[$i]->curComp) . "</span>";
        $r[] = form_input($inputSpec);
        $str .= $row_s . $cell_s . implode($cell_f . $cell_s, $r) . $cell_f . $row_f;
    }
    $str .= "</table>\n";
    return $str;
}

/**
 * Construct the sorting filter table
 * @param type $current_filter_values
 * @param type $cols
 * @return string
 */
function make_sorting_filter($current_filter_values, $cols) {
    $str = '';

    $hid = "<span class='filter_clear'>" . sorting_filter_vis_control() . "</span>";
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
    foreach ($current_filter_values as $sort) {
        $class = 'class="sorting_filter_input filter_input_field "';
        $cid = "id=\"qf_sort_col_$i\" ";
        $did = "id=\"qf_sort_dir_$i\" ";
        $action = " onchange='\$(\"qf_sort_dir_$i\").val\(\"ASC\"\)' "; // set default value on dir when changing column
        $c = form_dropdown('qf_sort_col[]', $col_sel, $sort['qf_sort_col'], $class . $cid . $action);
        $d = form_dropdown('qf_sort_dir[]', $dir_sel, $sort['qf_sort_dir'], $did);
        $str .= $row_s . $cell_s . $c . $cell_f . $cell_s . $d . $cell_f . $row_f;
        $i++;
    }
    $str .= "</table>\n";
    return $str;
}

/**
 * Construct the paging filter table
 * @param type $current_filter_values
 * @return type
 */
function make_paging_filter($current_filter_values) {
    $str = '';
    foreach ($current_filter_values as $name => $value) {
        $data = array(
            'id' => $name,
            'name' => $name,
            'value' => $value,
        );
        $str .= form_input($data);
    }
    return $str;
}

/**
 * Construct the column filter table
 * @param type $cols
 * @param type $col_filter
 * @param type $col_filter_size
 * @return string
 */
function make_column_filter($cols, $col_filter, $col_filter_size = 5) {
    $str = "";

    $options = array();
    foreach ($cols as $col) {
        if ($col[0] != '#') { // do not show columns with names that begin with hash
            $options[$col] = $col;
        }
    }
    $hid = "<span class='filter_clear'>" . column_filter_vis_control() . "</span>";
    $clr = "<span class='filter_clear'>" . filter_clear_control('cf_column_selection_ctl', 'gamma.clearSelector') . "</span>";
    $lab = "<span class='filter_label' >Column Filter</span>";
    $caption = "$lab $clr $hid";

    $str .= "<div class='filter_caption'>$caption</div>";
    $str .= "<div>";
    $str .= "<table class='FTab'><tr><td>";
    $str .= form_multiselect('cf_column_selection[]', $options, $col_filter, 'id="cf_column_selection_ctl" size="' . $col_filter_size . '" class="filter_col"');
    $str .= form_hidden('cf_column_selection_marker', 'yes');
    $str .= "</td></tr></table>";
    $str .= "</div>";
    return $str;
}
