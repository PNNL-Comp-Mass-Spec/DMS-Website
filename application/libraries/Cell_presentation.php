<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// Include the Number formatting methods
require_once(BASEPATH . '../application/libraries/Number_formatting.php');

/**
 * This class is used to format data in list reports, including adding hotlinks
 */
class Cell_presentation {

    private $hotlinks = array();
    var $col_filter = array();

    /**
     * Constructor
     */
    function __construct() {

    }

    /**
     * Initialize
     * @param mixed $cell_presentation_specs
     */
    function init($cell_presentation_specs) {
        $this->hotlinks = $cell_presentation_specs;
    }

    // --------------------------------------------------------------------
    private
        function get_display_cols($cols) {
        if (!empty($this->col_filter)) {
            $cols = $this->col_filter;
        }
        return $cols;
    }

    /**
     * Render a row
     * @param type $row
     * @return string
     */
    function render_row($row) {
        $str = "";
        $display_cols = $this->get_display_cols(array_keys($row));
        $colIndex = 0;
        foreach ($display_cols as $columnName) {
            // don't display columns that begin with hash character
            if ($columnName[0] == '#') {
                continue;
            }

            $value = $row[$columnName];
            $colSpec = null;
            if (array_key_exists($columnName, $this->hotlinks)) {
                $colSpec = $this->hotlinks[$columnName];
            } elseif (array_key_exists('@exclude', $this->hotlinks)) {
                if (!in_array($columnName, $this->hotlinks['@exclude']['Options'])) {
                    $colSpec = $this->hotlinks['@exclude'];
                }
            }
            if ($colSpec) {
                $str .= $this->render_hotlink($value, $row, $colSpec, NULL, $columnName, $colIndex);
            } else {
                $str .= "<td>" . $value . "</td>";
            }
            $colIndex++;
        }
        return $str;
    }

    /**
     * Render a hotlink as HTML, as specified by $colSpec["LinkType"]
     * @param mixed  $value     String or number
     * @param mixed  $row       Array of row data
     * @param mixed  $colSpec
     * @param type   $col_width Unused variable
     * @param string $col_name  Column name
     * @param int    $colIndex  Column index
     * @return string
     */
    private
        function render_hotlink($value, $row, $colSpec, $col_width, $col_name = '', $colIndex) {
        $str = "";
        // resolve target for hotlink
        $target = $colSpec["Target"];

        // resolve value to use for hotlink
        $whichArg = $colSpec["WhichArg"];
        $ref = $value;
        if ($whichArg != "") {
            switch ($whichArg) {
                case "value":
                    break;
                default:
                    $ref = $row[$whichArg];
                    break;
            }
        }

        // tooltip?
        $tool_tip = '';
        if (array_key_exists('ToolTip', $colSpec) && $colSpec['ToolTip']) {
            $tool_tip = "title='" . $colSpec['ToolTip'] . "'";
        }

        // render the hotlink
        switch ($colSpec["LinkType"]) {
            case "invoke_entity":
                // look for conditions on link
                // Supported condition is GreaterOrEqual
                $noLink = $this->evaulate_conditional($colSpec, $value);
                if ($noLink) {
                    $str .= "<td>$value</td>";
                } else {
                    // place target substitution marker
                    // (and preserve special primary filter characters)
                    if (strpos($target, '@') === FALSE) {
                        $sep = (substr($target, -1) == '~') ? '' : '/';
                        $target .= $sep . '@';
                    }
                    $url = reduce_double_slashes(site_url() . str_replace('@', $ref, $target));
                    $str .= "<td><a href='$url' $tool_tip>$value</a></td>";
                }
                break;
            case "invoke_multi_col":
                $cols = (array_key_exists('Options', $colSpec)) ? $colSpec['Options'] : array();
                foreach ($cols as $col => $v) {
                    if ($v) {
                        $cols[$col] = $col;
                    } else {
                        $cols[$col] = $row[$col];
                    }
                }
                $ref = implode('/', array_values($cols));
                $url = reduce_double_slashes(site_url() . "$target/$ref");
                $str .= "<td><a href='$url' $tool_tip>$value</a></td>";
                break;
            case "literal_link":
                $url = $target . $ref;
                $value = valueToString($value, $colSpec, FALSE);
                $str .= "<td><a href='$url' target='External$colIndex' $tool_tip>$value</a></td>";
                break;
            case "masked_link":
                $url = $target . $ref;
                if ($url) {
                    $lbl = getOptionValue($colSpec, 'Label', 'Undefined_masked_link');
                    $str .= "<td><a href='$url' target='External$colIndex' $tool_tip>$lbl</a></td>";
                } else {
                    $str .= "<td></td>";
                }
                break;
            case "CHECKBOX":
                // $str .= "<td>" . form_checkbox('ckbx', $ref, FALSE) . "</td>";
                $str .= "<td><input type='checkbox' value='$ref' name='ckbx' class='lr_ckbx'></td>";
                break;
            case "checkbox_json":
                // This is an old, unused mode
                $cols = (array_key_exists('Options', $colSpec)) ? $colSpec['Options'] : array();
                foreach ($cols as $col => $v) {
                    $cols[$col] = $row[$col];
                }
                $ref = implode('|', array_values($cols));
                $str .= "<td><input type='checkbox' value='$ref' name='ckbx' class='lr_ckbx'></td>";
                break;

            case "update_opener":
                $str .= "<td>" . "<a href='javascript:opener.epsilon.updateFieldValueFromChooser(\"" . $ref . "\", \"replace\")' >" . $value . "</a>" . "</td>";
                break;
            case "color_label":
                if (array_key_exists($ref, $colSpec["cond"])) {
                    $colorStyle = "class='" . $colSpec['cond'][$ref] . "'";
                } else {
                    $colorStyle = "";
                }
                $str .= "<td $colorStyle >$value</td>";
                break;
            case "bifold_choice":
                // This mode has been superseded by select_case
                $t = $colSpec['Options'];
                $target = ($ref == $target) ? $t[0] : $t[1];
                $url = reduce_double_slashes(site_url() . "$target/show/$value");
                $str .= "<td><a href='$url'>$value</a></td>";
                break;
            case "format_date":
                // Apply a custom date format, using the format code in the options column
                // Additionally, if the Target field for this hotlink definition contains an integer, that value is used for min_col_width
                // (this behavior is used because a given column cannot have two hotlinks defined for it)
                // For date format codes, see http://php.net/manual/en/function.date.php
                $dateValue = strtotime($value);
                if ($dateValue) {
                    $dateFormat = getOptionValue($colSpec, 'Format', 'Y-m-d H:i:s');
                    $value = date($dateFormat, $dateValue);
                }
                $str .= "<td>" . $value . "</td>";
                break;
            case "format_commas":
                $value = valueToString($value, $colSpec, TRUE);
                $str .= "<td>" . $value . "</td>";
                break;
            case "select_case":
                $t = $colSpec['Options'];
                $link_item = ($target) ? $row[$target] : $value;
                if (array_key_exists($ref, $t)) {
                    $link_base = $t[$ref];
                    if (strpos($link_base, '/') === FALSE) {
                        // Base link does not contain a forward slash; use /show/
                        $url = reduce_double_slashes(site_url() . "$link_base/show/$link_item");
                    } else {
                        // Base link includes a forward slash
                        $url = reduce_double_slashes(site_url() . "$link_base/$link_item");
                    }
                    $str .= "<td><a href='$url'>$value</a></td>";
                } else {
                    $str .= "<td>$value</td>";
                }
                break;
            case "copy_from":
                // Old, unused mode; superseded by "row_to_json" and "row_to_url"
                $url = reduce_double_slashes(site_url() . "$target/$ref");
                $str .= "<td><a href='$url'>$value</a></td>";
                break;
            case "row_to_url":
                $s = "";
                foreach ($row as $f => $v) {
                    $s .= ($s) ? '|' : '';
                    $s .= "$f@$v";
                }
                $url = reduce_double_slashes(site_url() . "$target");
                $str .= "<td><a href='javascript:void(0)' onclick='submitDynamicForm(\"$url\", \"$s\")'>$value</a></td>";
                break;
            case "row_to_json":
                $fsp = "";
                $rowAction = 'localRowAction';
                if (array_key_exists('Options', $colSpec)) {
                    $rowAction = getOptionValue($colSpec, 'rowAction', $rowAction);
                    if (array_key_exists('fields', $colSpec['Options'])) {
                        $fsp = ', "' . $colSpec['Options']['fields'] . '"';
                    }
                }
                foreach (array_keys($row) as $k) {
                    if ($row[$k] == null) {
                        $row[$k] = '';
                    }
                }
                $s = json_encode($row);
                $url = reduce_double_slashes(site_url() . "$target");
                $str .= "<td><a href='javascript:void(0)' onclick='$rowAction(\"$url\", \"$ref\", $s $fsp)'>$value</a></td>";
                break;
            case "masked_href-folder":
                $lbl = getOptionValue($colSpec, 'Label', 'Undefined_masked_href-folder');
                $lnk = str_replace('\\', '/', $ref);
                if ($lnk) {
                    $str = "<td>" . "<a href='file:///$lnk'>$lbl</a>" . "</td>";
                } else {
                    $str = "<td></td>";
                }
                break;
            case "href-folder":
                $lnk = str_replace('\\', '/', $ref);
                $str = "<td>" . "<a href='file:///$lnk'>$value</a>" . "</td>";
                break;
            case "inplace_edit":
                $className = str_replace(' ', '_', $col_name);
                $id = $className . '_' . $ref;
                $width = getOptionValue($colSpec, 'width', '0');

                $widthValue = filter_var($width, FILTER_VALIDATE_INT);
                if ($widthValue !== FALSE) {
                    $customSize = "size='$widthValue'";
                } else {
                    $customSize = '';
                }
                $str .= "<td><input class='$className' id='$id' name='$ref' value='$value' $customSize/></td>";
                break;

            case "link_list":
                $matches = array();
                $delim = (preg_match('/[,;]/', $ref, $matches)) ? $matches[0] : '';
                $flds = ($delim == '') ? array($ref) : explode($delim, $ref);
                $links = array();
                foreach ($flds as $ln) {
                    $ln = trim($ln);
                    $url = strncasecmp($ln, "http", 4) ? site_url() . $target . '/' . $ln : $ln;
                    $links[] = "<a href='$url'>$ln</a>";
                }
                $str .= "<td>" . implode($delim . ' ', $links) . "</td>";
                break;
            case "markup":
                $str .= "<td>" . nl2br($value) . "</td>";
                break;
            case "min_col_width":
                // No special rendering here, though get_cell_padding will pad the cell if the text is too short
                $str .= "<td>" . $value . "</td>";
                break;
            case "image_link":
                $url = $ref;
                $link_url = $url;
                if ($target) {
                    $url_parts = explode('/', $ref);
                    $last_seg = count($url_parts) - 1;
                    $url_parts[$last_seg] = $target;
                    $link_url = implode("/", $url_parts);
                }
                $width = getOptionValue($colSpec, 'width', '250');
                if ($url) {
                    $str .= "<td><a href='$link_url'><img src='$url' width='$width' border='0'></a></td>";
                } else {
                    $str .= "<td></td>";
                }
                break;
            case "column_tooltip":
                // If Decimals is defined in the options, format with the number of decimal places
                // If not defined, leave as-is
                $value = valueToString($value, $colSpec, FALSE);
                $str .= "<td>" . $value . "</td>";
                break;
            default:
                $str .= "<td>???" . $colSpec["LinkType"] . "???</td>";
                break;
        }
        return $str;
    }

    /**
     *
     * @param type $colSpec
     * @param type $ref
     * @param type $value
     * @return boolean
     */
    function evaulate_conditional($colSpec, $value) {
        $noLink = false;
        if (array_key_exists('Options', $colSpec)) {
            $test = getOptionValue($colSpec, 'GreaterOrEqual');
            if (!empty($test)) {
                if ($value < $test) {
                    $noLink = true;
                }
            }
            // more conditionals here when needed
        }
        return $noLink;
    }

    /**
     * Create HTML to display a set of column headers
     * @param type $rows
     * @param type $sorting_cols
     * @return string
     */
    function make_column_header($rows, $sorting_cols = array()) {
        if (empty($rows)) {
            return '';
        }
        $str = "";

        // which columns are showing
        $display_cols = $this->get_display_cols(array_keys(current($rows)));

        // get array of col sort makers
        $col_sort = $this->get_column_sort_markers($sorting_cols);

        foreach ($display_cols as $col_name) {
            if ($col_name[0] != '#') { // do not show columns with names that begin with hash
                // sorting marker
                $marker = $this->get_column_sort_marker($col_name, $col_sort);

                // Check for a column header tooltip
                $toolTip = $this->get_column_tooltip($col_name);
                if ($toolTip) {
                    $toolTip = 'title="' . $toolTip . '"';
                    $str .= '<th style="background-color:#C2E7F6;">';
                } else {
                    $toolTip = '';
                    $str .= '<th>';
                }

                // make header label
                $str .= $marker;
                $str .= "<a href='javascript:void(0)' onclick='lambda.setColSort(\"$col_name\")'  class='col_header' " . $toolTip . ">$col_name</a>";
                $str .= $this->get_cell_padding($col_name);
                $str .= "</th>";
            }
        }
        return "<tr>" . $str . "</tr>";
    }

    /**
     *
     * @param type $col_name
     * @param type $col_sort
     * @return string
     */
    private
        function get_column_sort_marker($col_name, $col_sort) {
        $marker = '';
        if (array_key_exists($col_name, $col_sort)) {
            $arrow = 'arrow_' . $col_sort[$col_name]->dir . $col_sort[$col_name]->precedence . '.png';
            $marker = "<img src='" . base_url() . "/images/$arrow' border='0' >";
        }
        return $marker;
    }

    /**
     * Return an array containing columns that will be used for
     * sorting and info about their precedence and direction.
     * accepts sorting column information in two different formats
     * and produces a common output format
     * @param type $sorting_cols
     * @return \stdClass
     */
    private
        function get_column_sort_markers($sorting_cols) {
        $col_sort = array();
        $sorting_precedence = 1;
        foreach ($sorting_cols as $obj) {
            if (is_object($obj)) { // query parts sorting spec format
                $sort_marker = new stdClass();
                $sort_marker->precedence = $sorting_precedence++;
                $sort_marker->dir = ($obj->dir == 'ASC') ? 'up' : 'down';
                $col_sort[$obj->col] = $sort_marker;
            } else
            if (is_array($obj)) { // raw sorting filter format
                $sort_marker = new stdClass();
                $sort_marker->precedence = $sorting_precedence++;
                $sort_marker->dir = ($obj['qf_sort_dir'] == 'ASC') ? 'up' : 'down';
                $col_sort[$obj['qf_sort_col']] = $sort_marker;
            }
        }
        return $col_sort;
    }

    /**
     *
     * @param type $col_name
     * @return type
     */
    private
        function get_cell_padding($col_name) {
        $padding = '';
        if (array_key_exists($col_name, $this->hotlinks)) {
            $colSpec = $this->hotlinks[$col_name];
            if ($colSpec["LinkType"] == 'min_col_width' ||
                $colSpec["LinkType"] == 'format_date') {
                if (is_numeric($colSpec["Target"])) {
                    $min_width = $colSpec["Target"];
                    $len = strlen($col_name);
                    if ($min_width > 0 && $len < $min_width) {
                        $padding = str_repeat("&nbsp;", $min_width - $len);
                    }
                }
            }
        }
        return $padding;
    }

    /**
     * Look for tooltip text associated with the given column
     * Checks for both col_name and +col_name entries
     * @param type $col_name
     * @return type
     */
    private
        function get_column_tooltip($col_name) {
        $toolTip = $this->get_column_tooltip_work($col_name);
        if (empty($toolTip)) {
            // ToolTip was not found using the column name
            // Check for a name that is preceded by a plus sign
            // This is used on pages where we have both a column_tooltip and a literal_link (or some other link) on the column
            // For example, in page family dataset_pm_and_psm:
            //   XIC_FWHM_Q3 defines a literal_link to a SMAQC page
            //   +XIC_FWHM_Q3 defines the tooltip for the XIC_FWHM_Q3 column
            $toolTip = $this->get_column_tooltip_work('+' . $col_name);
        }
        return $toolTip;
    }

    /**
     *  Look for tooltip text associated with the given column
     * @param type $col_name_to_find
     * @return type
     */
    private
        function get_column_tooltip_work($col_name_to_find) {
        $toolTip = '';
        if (array_key_exists($col_name_to_find, $this->hotlinks)) {
            $colSpec = $this->hotlinks[$col_name_to_find];
            if ($colSpec["LinkType"] == 'column_tooltip') {
                $toolTip = $colSpec["Target"];
            }
        }
        return $toolTip;
    }

    /**
     * Update the date columns to have user-friendly dates
     * @param type $result
     * @param type $col_info
     * @return type
     */
    function fix_datetime_display(&$result, $col_info) {
        // get list of datetime columns
        $dc = array();
        foreach ($col_info as $f) {
            // mssql returns 'datetime', sqlsrv returns 93 (SQL datetime)
            if ($f->type === 'datetime' || $f->type === 93) {
                $dc[] = $f->name;
            }
        }

        if (count($dc) == 0) {
            // No fields are type datetime; nothing to update
            return;
        }

        // Traverse the array of rows, and fix the datetime column formats
        //
        // Get the date display format from global preferences
        $CI = & get_instance();
        $CI->load->model('dms_preferences', 'preferences');
        $dateFormat = $CI->preferences->get_date_format_string();

        // traverse all the rows in the result
        for ($i = 0; $i < count($result); $i++) {
            // traverse all the date columns in the current row
            foreach ($dc as $col) {
                // skip if the column value is empty
                if (!isset($result[$i][$col])) {
                    continue;
                }

                // convert to blank if column value is null
                if (is_null($result[$i][$col])) {
                    $result[$i][$col] = '';
                } else {
                    // convert original date string to date object
                    // and then convert that to desired display format.
                    // mark display if original format could not be parsed.
                    $dt = false;
                    if (is_string($result[$i][$col])) {
                        $dt = strtotime($result[$i][$col]);
                    }
                    else {
                        $dt = $result[$i][$col];
                    }
                    if ($dt) {
                        $result[$i][$col] = date($dateFormat, $dt);
                    } else {
                        $result[$i][$col] = "??" . $result[$i][$col];
                    }
                }
            }
        }
    }

    /**
     * Update the decimal columns to have user-friendly doubles
     * @param type $result
     * @param type $col_info
     * @return type
     */
    function fix_decimal_display(&$result, $col_info) {
        // get list of decimal columns
        $dc = array();
        foreach ($col_info as $f) {
            // mssql returns decimals as doubles (and 'real' type), sqlsrv returns 3 (SQL decimal)
            if ($f->type === 'real' || $f->type === 3) {
                $dc[] = $f->name;
            }
        }

        if (count($dc) == 0) {
            // No fields are type decimals; nothing to update
            return;
        }

        // Traverse the array of rows, and fix the decimal column formats
        //
        // traverse all the rows in the result
        for ($i = 0; $i < count($result); $i++) {
            // traverse all the decimal columns in the current row
            foreach ($dc as $col) {
                // skip if the column value is empty
                if (!isset($result[$i][$col])) {
                    continue;
                }

                // convert to blank if column value is null
                if (is_null($result[$i][$col])) {
                    $result[$i][$col] = '';
                } else {
                    // convert original decimal string to double
                    // if it is not a string, don't touch it.
                    if (is_string($result[$i][$col])) {
                        $result[$i][$col] = doubleval($result[$i][$col]);
                    }
                }
            }
        }
    }

    /**
     * Update the decimal and datetime columns to have user-friendly doubles and dates
     * @param type $result
     * @param type $col_info
     * @return type
     */
    function fix_datetime_and_decimal_display(&$result, $col_info) {
        $this->fix_datetime_display($result, $col_info);
        $this->fix_decimal_display($result, $col_info);
    }

    /**
     *
     * @param type $col_filter
     */
    function set_col_filter($col_filter) {
        $this->col_filter = $col_filter;
    }

}
