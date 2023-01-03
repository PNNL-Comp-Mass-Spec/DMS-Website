<?php
namespace App\Libraries;

/**
 * This class is used to format data in list reports, including adding hotlinks
 */
class Cell_presentation {

    private $hotlinks = array();

    /**
     * List of specific columns to show
     * Empty array if showing all columns
     * @var type Array of strings
     */
    var $col_filter = array();

    var $url_updater;
    var $label_formatter;

    /**
     * Constructor
     */
    function __construct() {
        // Include the URL updater class
        $this->url_updater = new \App\Libraries\URL_updater();
        $this->label_formatter = new \App\Libraries\Label_formatter();

        // Include the Number formatting methods
        helper('number_formatting');
    }

    /**
     * Initialize
     * @param mixed $cell_presentation_specs
     */
    function init($cell_presentation_specs) {
        //$this->hotlinks = $cell_presentation_specs;
        $this->hotlinks = array_change_key_case($cell_presentation_specs, CASE_LOWER);
    }

    /**
     * Get an array of column names to export
     * @param type $result
     */
    function get_columns_to_export(&$result) {

        // This array tracks all of the column names in $result
        $cols = array_keys(current($result));

        // This array tracks columns to include
        $col_filter = array();

        foreach ($cols as $columnName) {

            // Look for an entry in $this->hotlinks that matches either this column name,
            // or this column name preceded by one or more plus signs
            $colSpec = $this->get_colspec_with_link_type($columnName, "no_export");
            $colSpec2 = $this->get_colspec_with_link_type($columnName, "no_display");
            if (!$colSpec && !$colSpec2) {
                $formatted = $this->label_formatter->format($columnName);
                $colSpec = $this->get_colspec_with_link_type($formatted, "no_export");
                $colSpec2 = $this->get_colspec_with_link_type($formatted, "no_display");
            }

            if (!$colSpec && !$colSpec2) {
                // Include this column (since no hotlink of type no_export is defined)
                $col_filter[] = $columnName;
            }
        }

        return $col_filter;
    }

    /**
     * Look for a hotlink of the given type; return it if found or null if not found
     * Matches both $columnName and hotlinks named with one or more plus signs followed by $columnName
     * @param type $columnName
     * @param type $linkTypeName
     * @return type
     */
    private function get_colspec_with_link_type($columnName, $linkTypeName) {

        // Look for a hotlink name that matches this column name
        if (array_key_exists(strtolower($columnName), $this->hotlinks)) {
            $colSpec = $this->hotlinks[strtolower($columnName)];

            if ($colSpec["LinkType"] == $linkTypeName) {
                return $colSpec;
            }
        }

        // Look for a hotlink name that matches this column name, preceded by one or more plus signs
        $columnNameWithPlusSigns = '+' . $columnName;

        while (true) {
            if (array_key_exists(strtolower($columnNameWithPlusSigns), $this->hotlinks)) {
                $colSpec = $this->hotlinks[strtolower($columnNameWithPlusSigns)];

                if ($colSpec["LinkType"] == $linkTypeName) {
                    return $colSpec;
                }
            } else {
                return null;
            }

            // The key was found, but the LinkType was not $linkTypeName
            // Add another plus sign
            $columnNameWithPlusSigns = '+' . $columnNameWithPlusSigns;
        }
    }

    /**
     * Get list of columns to show
     * @param array $cols All columns
     * @return array Returns all columns if $this->col_filter is empty
     */
    private function get_display_cols($cols) {
        if (!empty($this->col_filter)) {
            $cols = $this->col_filter;
        }
        return $cols;
    }

    /**
     * Get an array listing the horizontal alignment mode for each column
     * @param type $result
     * @return type
     */
    function get_column_alignment(&$result) {

        // This array tracks all of the column names in $result
        $cols = array_keys(current($result));

        // This array tracks columns to include
        $col_alignment = array();

        foreach ($cols as $columnName) {

            // Look for an entry in $this->hotlinks that matches either this column name,
            // or this column name preceded by one or more plus signs
            $colSpec = $this->get_colspec_with_link_type($columnName, "export_align");
            if (!$colSpec) {
                $formatted = $this->label_formatter->format($columnName);
                $colSpec = $this->get_colspec_with_link_type($formatted, "export_align");
            }

            if ($colSpec && array_key_exists('Options', $colSpec)) {
                // Examine the Options to determine the alignment
                $t = $colSpec['Options'];

                if (array_key_exists('Align', $t)) {
                    $col_alignment[$columnName] = $t['Align'];
                    continue;
                }
            }

            $col_alignment[$columnName] = 'default';
        }

        return $col_alignment;
    }

    /**
     * Look for items in $result that would be colored by render_hotlink
     * Add a color code to the start of the cell so that export_to_excel in export_helper.php
     * can set the background color and text color for the cell
     *
     * Note: this method is only called when data is exported to an Excel file
     *
     * @param type $result
     * @return type
     */
    function add_color_codes(&$result) {

        $cols = array_keys(current($result));

        // traverse all the rows in the result
        for ($i = 0; $i < count($result); $i++) {
            $row = $result[$i];

            // Traverse all the columns in the current row
            // Cache any color codes that need to be applied

            // Keys in this array are column name; values are color code text
            $colorCodesByColumn = array();

            // Keys in this array are column name; values are the name of a different column to copy the color from
            $copyFromByColumn = array();

            foreach ($cols as $columnName) {
                $value = $row[$columnName];
                $formatted = $this->label_formatter->format($columnName);

                if (array_key_exists(strtolower($columnName), $this->hotlinks)) {
                    $colSpec = $this->hotlinks[strtolower($columnName)];
                    $colorCode = $this->get_color_code($value, $row, $colSpec);
                } elseif (array_key_exists(strtolower($formatted), $this->hotlinks)) {
                    $colSpec = $this->hotlinks[strtolower($formatted)];
                    $colorCode = $this->get_color_code($value, $row, $colSpec);
                } else {
                    $colorCode = "";
                }

                if (empty($colorCode)) {
                    // Look for an entry in $this->hotlinks that matches either this column name,
                    // or this column name preceded by a plus sign, and is of type "color_label"
                    $colSpec2 = $this->get_colspec_with_link_type($columnName, "color_label");
                    if (!$colSpec2) {
                        $colSpec2 = $this->get_colspec_with_link_type($formatted, "color_label");
                    }

                    if ($colSpec2) {
                        $colorCode = $this->get_color_code($value, $row, $colSpec2);
                    }
                }

                if ($colorCode != "") {
                    $colorCodesByColumn[$columnName] = $colorCode;
                }

                // Look for an entry in $this->hotlinks that matches either this column name,
                // or this column name preceded by a plus sign, and is of type "copy_color_from"
                $colSpec3 = $this->get_colspec_with_link_type($columnName, "copy_color_from");
                if (!$colSpec3) {
                    $colSpec3 = $this->get_colspec_with_link_type($formatted, "copy_color_from");
                }

                if ($colSpec3) {
                    $whichArg = $colSpec3["WhichArg"];

                    if (!empty($whichArg) && $whichArg != 'value') {
                        $copyFromByColumn[$columnName] = $whichArg;
                    }
                }
            }

            foreach ($cols as $columnName) {
                if (array_key_exists($columnName, $colorCodesByColumn)) {
                    $colorCode = $colorCodesByColumn[$columnName];
                    $result[$i][$columnName] = $colorCode . $row[$columnName];
                } else {
                    if (array_key_exists($columnName, $copyFromByColumn)) {
                        $copyColorFrom = $copyFromByColumn[$columnName];

                        if (array_key_exists($copyColorFrom, $colorCodesByColumn)) {
                            $colorCode = $colorCodesByColumn[$copyColorFrom];
                            $result[$i][$columnName] = $colorCode . $row[$columnName];
                        }
                    }
                }
            }
        }

    }

    /**
     * Get the color code string for the given data item
     * Color names are defined in CSS file base.css
     *
     * This method is only called when data is exported to an Excel file
     *
     * Method export_to_excel in file export_helper.php looks for a format string
     * that starts with ##FORMAT and, if found, uses the text color, fill color,
     * and text style to format the cell
     *
     * @param type $value
     * @param type $colSpec
     * @return string
     */
    private function get_color_code($value, $row, $colSpec) {
        $colorCode = "";

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

        if ($colSpec["LinkType"] == "color_label") {
            if (array_key_exists($ref, $colSpec['cond'])) {
                $cssClass = $colSpec['cond'][$ref];

                $textColor = "";
                $fillColor = "";
                $textStyle = "";

                $black = "000000";
                $green = "008000";
                $red =   "FF0000";

                # Color names are defined in CSS file base.css

                switch ($cssClass) {
                    case "bad_clr":
                        # Red text (FF0000)
                        $textColor = $red;
                        break;
                    case "warning_clr":
                        # Orange text
                        $textColor = "FF8C00";
                        break;
                    case "enabled_clr":
                        # Green text
                        $textColor = $green;
                        break;
                    case "violet_background":
                        # Violet background
                        $fillColor = "D9CCFF";
                        break;
                    case "clr_30":
                        # Light green background
                        $fillColor = "E5FFE5";
                        break;
                    case "clr_45":
                        # Light blue background
                        $fillColor = "CCECFF";
                        break;
                    case "clr_60":
                        # Light yellow background
                        $fillColor = "FFFF75";
                        break;
                    case "clr_80":
                        # Orange background with black text
                        $fillColor = "FF8C00";
                        $textColor = $black;
                        break;
                    case "clr_90":
                        # Orange background with bold black text
                        $fillColor = "FF8C00";
                        $textColor = $black;
                        $textStyle = "bold";
                        break;
                    case "clr_120":
                        # Red background (FF0000) with bold white text
                        $fillColor = $red;
                        $textColor = "FFF5EE";
                        $textStyle = "bold";
                        break;
                }

                if ($textColor != "" || $fillColor != "" || $textStyle != "") {
                    if ($textColor == "") {
                        $textColor = "default";
                    }

                    if ($fillColor == "") {
                        $fillColor = "default";
                    }

                    if ($textStyle == "") {
                        $textStyle = "default";
                    }

                    # Method export_to_excel in file export_helper.php looks for this string to determine if a cell should be colored
                    $colorCode = "##FORMAT_[$textColor]_[$fillColor]_[$textStyle]##";
                }
            }
        }

        return $colorCode;
    }

    /**
     * Determine the color style to use based on the condition for the column spec and the given reference value
     *   Requires an exact match to the value
     * Color styles are defined in file base.css
     * @param type $colSpec
     * @param type $ref
     * @return string
     */
    private function get_color_style($colSpec, $ref) {
        if (array_key_exists($ref, $colSpec['cond'])) {
            // The options array contains $ref; use the specified color
            // For example, given options array {"0":"clr_30","1":"clr_45","2":"clr_60","3":"clr_120"}
            // if $ref is "2", $colorStyle will be "class='clr_60'";
            $colorStyle = "class='" . $colSpec['cond'][$ref] . "'";
        } else {
            $colorStyle = "";
        }

        return $colorStyle;
    }

    /**
     * Get the color style for a hot link; return an empty string if no color
     * Color styles are defined in file base.css
     * @param type $row
     * @param type $columnName
     * @param type $value
     */
    private function get_hotlink_color_style($row, $columnName, $value) {

        // Look for a hotlink name that matches this column name, preceded by one or more plus signs
        $columnNameWithPlusSigns = '+' . $columnName;

        while (true) {
            if (array_key_exists(strtolower($columnNameWithPlusSigns), $this->hotlinks)) {
                $colSpec = $this->hotlinks[strtolower($columnNameWithPlusSigns)];

                if ($colSpec["LinkType"] == 'color_label') {
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

                    return ' ' . $this->get_color_style($colSpec, $ref);
                }

                // The key was found, but the LinkType was not 'color_label'
                // Add another plus sign
                $columnNameWithPlusSigns = '+' . $columnNameWithPlusSigns;
            } else {
                return "";
            }
        }
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

            $formatted = $this->label_formatter->format($columnName);

            // Look for an entry in $this->hotlinks that matches either this column name,
            // or this column name preceded by one or more plus signs
            $colSpec = $this->get_colspec_with_link_type($columnName, "no_display");
            if (!$colSpec) {
                $colSpec = $this->get_colspec_with_link_type($formatted, "no_display");
            }

            if ($colSpec) {
                // Skip this column (since a hotlink of type no_display is defined)
                continue;
            }

            $value = $row[$columnName];
            $colSpec = null;

            if (array_key_exists(strtolower($columnName), $this->hotlinks)) {
                $colSpec = $this->hotlinks[strtolower($columnName)];
            } elseif (array_key_exists(strtolower($formatted), $this->hotlinks)) {
                $colSpec = $this->hotlinks[strtolower($formatted)];
            } elseif (array_key_exists('@exclude', $this->hotlinks)) {
                if (!in_array($columnName, $this->hotlinks['@exclude']['Options'])) {
                    $colSpec = $this->hotlinks['@exclude'];
                }
            }

            if ($colSpec) {
                # $colSpec includes information for either creating an HTML link to another page or for formatting the cell
                # For details, see https://prismwiki.pnl.gov/wiki/DMS_Config_DB_Help_list_report_hotlinks

                # Look for another hotlink that defines a color style for this cell
                $colorStyle = $this->get_hotlink_color_style($row, $columnName, $value);
                if ($colorStyle === "") {
                    $colorStyle = $this->get_hotlink_color_style($row, $formatted, $value);
                }

                $str .= $this->render_hotlink($value, $row, $colSpec, $columnName, $colIndex, $colorStyle);
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
     * @param string $columnName  Column name
     * @param int    $colIndex    Column index
     * @param string $colorStyle  Color style (preceded by a space), or an empty string
     * @return string
     */
    private function render_hotlink($value, $row, $colSpec, $columnName = '', $colIndex, $colorStyle) {

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
                    if (array_key_exists(strtolower($whichArg), $row)) {
                        $whichArg = strtolower($whichArg);
                    }
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
                // Look for conditions on link
                // Supported condition is GreaterOrEqual
                $noLink = $this->evaulate_conditional($colSpec, $value);
                if ($noLink) {
                    // The value is too small; do not link to it
                    $str .= "<td>$value</td>";
                } else {
                    // Place target substitution marker
                    // (and preserve special primary filter characters)
                    if (strpos($target, '@') === false) {
                        $sep = (substr($target, -1) == '~') ? '' : '/';
                        $target .= $sep . '@';
                    }
                    $url = reduce_double_slashes(site_url(str_replace('@', $ref, $target)));

                    $str .= "<td$colorStyle><a href='$url' $tool_tip>$value</a></td>";
                }
                break;

            case "invoke_multi_col":
                $cols = (array_key_exists('Options', $colSpec)) ? $colSpec['Options'] : array();
                foreach ($cols as $columnName => $v) {
                    if ($v) {
                        $cols[$columnName] = $columnName;
                    } else {
                        if (array_key_exists($columnName, $row)) {
                            $cols[$columnName] = $row[$columnName];
                        } else {
                            $cols[$columnName] = $row[$this->label_formatter->deformat($columnName)];
                        }
                    }
                }
                $ref = implode('/', array_values($cols));
                $url = reduce_double_slashes(site_url("$target/$ref"));
                $str .= "<td><a href='$url' $tool_tip>$value</a></td>";
                break;

            case "literal_link":
                //$url = $target . $ref;
                $url = $this->url_updater->fix_link($target . $ref);
                $value = valueToString($value, $colSpec, false);
                $str .= "<td><a href='$url' target='External$colIndex' $tool_tip>$value</a></td>";
                break;

            case "masked_link":
                //$url = $target . $ref;
                $url = $this->url_updater->fix_link($target . $ref);
                if ($url) {
                    $lbl = getOptionValue($colSpec, 'Label', 'Undefined_masked_link');
                    $str .= "<td><a href='$url' target='External$colIndex' $tool_tip>$lbl</a></td>";
                } else {
                    $str .= "<td></td>";
                }
                break;

            case "CHECKBOX":
                // $str .= "<td>" . form_checkbox('ckbx', $ref, false) . "</td>";
                $str .= "<td><input type='checkbox' value='$ref' name='ckbx' class='lr_ckbx'></td>";
                break;

            case "checkbox_json":
                // This is an old, unused mode
                $cols = (array_key_exists('Options', $colSpec)) ? $colSpec['Options'] : array();
                foreach ($cols as $columnName => $v) {
                    $cols[$columnName] = $row[$columnName];
                }
                $ref = implode('|', array_values($cols));
                $str .= "<td><input type='checkbox' value='$ref' name='ckbx' class='lr_ckbx'></td>";
                break;

            case "update_opener":
                $str .= "<td>" . "<a href='javascript:opener.epsilon.updateFieldValueFromChooser(\"" . $ref . "\", \"replace\")' >" . $value . "</a>" . "</td>";
                break;

            case "color_label":
                // Color this column based on the value in $ref (which either came from this column or from another column, specified by WhichArg)
                $colorStyle = $this->get_color_style($colSpec, $ref);

                $str .= "<td $colorStyle>$value</td>";
                break;

            case "doi_link":
                $linkOrValue = $this->url_updater->get_doi_link($ref, $colIndex);
                $str .= "<td>$linkOrValue</td>";
                break;

            case "bifold_choice":
                // This mode has been superseded by select_case
                $t = $colSpec['Options'];
                $target = ($ref == $target) ? $t[0] : $t[1];
                $url = reduce_double_slashes(site_url("$target/show/$value"));
                $str .= "<td><a href='$url'>$value</a></td>";
                break;

            case "format_date":
                // Apply a custom date format, using the format code in the options column
                // For date format codes, see http://php.net/manual/en/function.date.php
                //
                // If the Target field for this hotlink definition contains an integer,
                // that value is used for min_col_width (this behavior is used because a given column
                // cannot have two hotlinks defined for it; see method get_cell_padding)
                $dateValue = strtotime($value);
                if ($dateValue) {
                    $dateFormat = getOptionValue($colSpec, 'Format', 'Y-m-d H:i:s');
                    $value = date($dateFormat, $dateValue);
                }
                $str .= "<td>" . $value . "</td>";
                break;

            case "format_commas":
                $value = valueToString($value, $colSpec, true);
                $str .= "<td>" . $value . "</td>";
                break;

            case "select_case":
                $t = $colSpec['Options'];
                $link_item = ($target) ? $row[$target] : $value;
                if (array_key_exists($ref, $t)) {
                    $link_base = $t[$ref];
                    if (strpos($link_base, '/') === false) {
                        // Base link does not contain a forward slash; use /show/
                        $url = reduce_double_slashes(site_url("$link_base/show/$link_item"));
                    } else {
                        // Base link includes a forward slash
                        $url = reduce_double_slashes(site_url("$link_base/$link_item"));
                    }
                    $str .= "<td><a href='$url'>$value</a></td>";
                } else {
                    $str .= "<td>$value</td>";
                }
                break;

            case "copy_from":
                // Old, unused mode; superseded by "row_to_json" and "row_to_url"
                $url = reduce_double_slashes(site_url("$target/$ref"));
                $str .= "<td><a href='$url'>$value</a></td>";
                break;

            case "row_to_url":
                $s = "";
                foreach ($row as $f => $v) {
                    $s .= ($s) ? '|' : '';
                    $s .= "$f@$v";
                }
                $url = reduce_double_slashes(site_url("$target"));
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
                $url = reduce_double_slashes(site_url("$target"));
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
                $className = str_replace(' ', '_', $columnName);
                $id = $className . '_' . $ref;
                $width = getOptionValue($colSpec, 'width', '0');

                $widthValue = filter_var($width, FILTER_VALIDATE_INT);
                if ($widthValue !== false) {
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
                    $url = strncasecmp($ln, "http", 4) ? site_url($target . '/' . $ln) : $ln;
                    $url_fixed = $this->url_updater->fix_link($url);
                    $links[] = "<a href='$url_fixed'>$ln</a>";
                }
                $str .= "<td>" . implode($delim . ' ', $links) . "</td>";
                break;

            case "markup":
                // Display text with carriage returns
                // Converts newlines to <br>
                //
                // If the Target field for this hotlink definition contains an integer,
                // that value is used for min_col_width (this behavior is used because a given column
                // cannot have two hotlinks defined for it; see method get_cell_padding)
                $str .= "<td>" . nl2br($value) . "</td>";
                break;

            case "min_col_width":
                // No special rendering here, though get_cell_padding will pad the cell if the text is too short
                $str .= "<td>" . $value . "</td>";
                break;

            case "image_link":
                //$url = $ref;
                $url = $this->url_updater->fix_link($ref);
                $link_url = $url;
                if ($target) {
                    $url_parts = explode('/', $ref);
                    $last_seg = count($url_parts) - 1;
                    $url_parts[$last_seg] = $target;
                    //$link_url = implode("/", $url_parts);
                    $link_url = $this->url_updater->fix_link(implode("/", $url_parts));
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
                $value = valueToString($value, $colSpec, false);
                $str .= "<td>" . $value . "</td>";
                break;

            case "copy_color_from":
            case "no_export":
            case "export_align":
                // These only affect data export
                $str .= "<td>" . $value . "</td>";
                break;
            case "no_display":
                // Not displayed, just ignore it (this is a safety catch, we shouldn't even enter this function for no_display columns)
                break;

            default:
                $str .= "<td>???" . $colSpec["LinkType"] . "???</td>";
                break;
        }
        return $str;
    }

    /**
     * Look "GreaterOrEqual" in the options section of this colSpec
     * If found, compare $value to the specified value,
     * returning true if $value is lest than the value defined in the options
     * Otherwise, return false
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

        foreach ($display_cols as $columnName) {
            if ($columnName[0] == '#') { // do not show columns with names that begin with hash
                continue;
            }

            $formatted = $this->label_formatter->format($columnName);
            // Look for an entry in $this->hotlinks that matches either this column name,
            // or this column name preceded by one or more plus signs
            $colSpec = $this->get_colspec_with_link_type($columnName, "no_display");
            if (!$colSpec) {
                $colSpec = $this->get_colspec_with_link_type($formatted, "no_display");
            }

            if ($colSpec) {
                // Skip this column (since a hotlink of type no_display is defined)
                continue;
            }

            // sorting marker
            $marker = $this->get_column_sort_marker($columnName, $col_sort);

            // Check for a column header tooltip
            $toolTip = $this->get_column_tooltip($columnName);
            if ($toolTip) {
                $toolTip = 'title="' . $toolTip . '"';
                $str .= '<th style="background-color:#C2E7F6;">';
            } else {
                $toolTip = '';
                $str .= '<th>';
            }

            $clickToSort = " onclick='lambda.setColSort(\"$columnName\")'";
            if ($columnName == 'Sel' || $columnName == 'sel') { // Do not allow sorting by the check box column
                $clickToSort = "";
            }

            // make header label
            $str .= $marker;
            $str .= "<a href='javascript:void(0)'" . $clickToSort . " class='col_header' " . $toolTip . ">$formatted</a>";
            $padding = $this->get_cell_padding($columnName);
            if ($padding === '') {
                $padding = $this->get_cell_padding($formatted);
            }
            $str .= $padding;
            $str .= "</th>";
        }
        return "<tr>" . $str . "</tr>";
    }

    /**
     *
     * @param type $columnName
     * @param type $col_sort
     * @return string
     */
    private function get_column_sort_marker($columnName, $col_sort) {
        $marker = '';
        if (array_key_exists($columnName, $col_sort)) {
            $arrow = 'arrow_' . $col_sort[$columnName]->dir . $col_sort[$columnName]->precedence . '.png';
            $marker = "<img src='" . base_url("/images/$arrow") . "' border='0' >";
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
    private function get_column_sort_markers($sorting_cols) {
        $col_sort = array();
        $sorting_precedence = 1;
        foreach ($sorting_cols as $obj) {
            if (is_object($obj)) { // query parts sorting spec format
                $sort_marker = new \stdClass();
                $sort_marker->precedence = $sorting_precedence++;
                $sort_marker->dir = ($obj->dir == 'ASC') ? 'up' : 'down';
                $col_sort[$obj->col] = $sort_marker;
            } else
            if (is_array($obj)) { // raw sorting filter format
                $sort_marker = new \stdClass();
                $sort_marker->precedence = $sorting_precedence++;
                $sort_marker->dir = ($obj['qf_sort_dir'] == 'ASC') ? 'up' : 'down';
                $col_sort[$obj['qf_sort_col']] = $sort_marker;
            }
        }
        return $col_sort;
    }

    /**
     * Optionally assure that the cell is a minimum target width
     * @param type $columnName
     * @return type
     */
    private function get_cell_padding($columnName) {
        $padding = '';
        if (array_key_exists(strtolower($columnName), $this->hotlinks)) {
            $colSpec = $this->hotlinks[strtolower($columnName)];
            if ($colSpec["LinkType"] == 'min_col_width' ||
                    $colSpec["LinkType"] == 'format_date' ||
                    $colSpec["LinkType"] == 'markup') {
                if (is_numeric($colSpec["Target"])) {
                    $min_width = $colSpec["Target"];
                    $len = strlen($columnName);
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
     * @param type $columnName
     * @return type
     */
    private function get_column_tooltip($columnName) {
        $toolTip = $this->get_column_tooltip_work($columnName);
        if (empty($toolTip)) {
            // ToolTip was not found using the column name
            // Check for a name that is preceded by a plus sign
            // This is used on pages where we have both a column_tooltip and a literal_link (or some other link) on the column
            // For example, in page family dataset_pm_and_psm:
            //   XIC_FWHM_Q3 defines a literal_link to a SMAQC page
            //   +XIC_FWHM_Q3 defines the tooltip for the XIC_FWHM_Q3 column
            $toolTip = $this->get_column_tooltip_work('+' . $columnName);
        }
        return $toolTip;
    }

    /**
     *  Look for tooltip text associated with the given column
     * @param type $columnNameToFind
     * @return type
     */
    private function get_column_tooltip_work($columnNameToFind) {
        $toolTip = '';
        $formatted = $this->label_formatter->format($columnNameToFind);
        if (array_key_exists(strtolower($columnNameToFind), $this->hotlinks)) {
            $colSpec = $this->hotlinks[strtolower($columnNameToFind)];
            if ($colSpec["LinkType"] == 'column_tooltip') {
                $toolTip = $colSpec["Target"];
            }
        } elseif (array_key_exists(strtolower($formatted), $this->hotlinks)) {
            $colSpec = $this->hotlinks[strtolower($formatted)];
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
        $dateTimeColumns = array();
        foreach ($col_info as $f) {
            // mssql returns 'datetime', sqlsrv returns 93 (SQL datetime)
            if ($f->type === 'datetime' || $f->type === 93) {
                $dateTimeColumns[] = $f->name;
            }

            // postgres driver: types 'timestamp' and 'timestamptz'
            // Could possibly add others (see Sql_postgre.php for type codes)
            if ($f->type === 1114 || $f->type === 1184) {
                $dateTimeColumns[] = $f->name;
            }
        }

        if (count($dateTimeColumns) == 0) {
            // No fields are type datetime; nothing to update
            return;
        }

        // Traverse the array of rows, and fix the datetime column formats
        //
        // Get the date display format from global preferences
        $preferences = model('App\Models\Dms_preferences');
        $dateFormat = $preferences->get_date_format_string();

        // traverse all the rows in the result
        for ($i = 0; $i < count($result); $i++) {
            // traverse all the date columns in the current row
            foreach ($dateTimeColumns as $columnName) {
                // skip if the column value is empty
                if (!isset($result[$i][$columnName])) {
                    continue;
                }

                // convert to blank if column value is null
                if (is_null($result[$i][$columnName])) {
                    $result[$i][$columnName] = '';
                } else {
                    // convert original date string to date object
                    // and then convert that to desired display format.
                    // mark display if original format could not be parsed.
                    $dt = false;
                    if (is_string($result[$i][$columnName])) {
                        $dt = strtotime($result[$i][$columnName]);
                    } else {
                        $dt = $result[$i][$columnName];
                    }
                    if ($dt) {
                        $result[$i][$columnName] = date($dateFormat, $dt);
                    } else {
                        $result[$i][$columnName] = "??" . $result[$i][$columnName];
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
        $decimalColumns = array();
        foreach ($col_info as $f) {
            // mssql returns decimals as doubles (and 'real' type), sqlsrv returns 3 (SQL decimal)
            if ($f->type === 'real' || $f->type === 3) {
                $decimalColumns[] = $f->name;
            }
        }

        if (count($decimalColumns) == 0) {
            // No fields are type decimals; nothing to update
            return;
        }

        // Traverse the array of rows, and fix the decimal column formats
        //
        // traverse all the rows in the result
        for ($i = 0; $i < count($result); $i++) {
            // traverse all the decimal columns in the current row
            foreach ($decimalColumns as $columnName) {
                // skip if the column value is empty
                if (!isset($result[$i][$columnName])) {
                    continue;
                }

                // convert to blank if column value is null
                if (is_null($result[$i][$columnName])) {
                    $result[$i][$columnName] = '';
                } else {
                    // convert original decimal string to double
                    // if it is not a string, don't touch it.
                    if (is_string($result[$i][$columnName])) {
                        $result[$i][$columnName] = doubleval($result[$i][$columnName]);
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
     * Set the list of columns to show
     * @param array $col_filter
     */
    function set_col_filter($col_filter) {
        $this->col_filter = $col_filter;
    }
}
?>
