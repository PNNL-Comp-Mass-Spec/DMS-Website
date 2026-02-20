<?php

/**
 * Create HTML to display detail report fields, including adding hotlinks
 * Also adds the header fields, including the link to the list report and edit/entry buttons
 * This method is called from view app/Views/main/detail_report_data.php
 * That view is loaded from method show_data in DmsBase.php
 * @param array $columns
 * @param array $fields
 * @param array $hotlinks
 * @param string $controller_name
 * @param mixed $id
 * @param bool $show_entry_links
 * @param bool $show_create_links
 * @return string
 */
function make_detail_report_section(array $columns, array $fields, array $hotlinks, string $controller_name, $id, bool $show_entry_links, bool $show_create_links): string {
    $str = '';

    // Fields are contained in a table
    $str .= "\n<table class='DRep' >\n";

    $str .= "<tr>";
    $str .= "<th align='left'>";
    $str .= "<a title='Back to the list report' href='../report'><img src='" . base_url('/images/page_white_get.png') . "' border='0' ></img></a>";
    $str .= "</th>";
    $str .= "<th>";

    if ($show_entry_links) {
        $str .= make_detail_report_edit_links($controller_name, $id, $show_create_links);
    }
    $str .= "</th>";
    $str .= "</tr>";

    $str .= "<tr>";
    $str .= "<th>Parameter</th>";
    $str .= "<th>Value</th>";
    $str .= "</tr>";

    $str .= make_detail_table_data_rows($columns, $fields, $hotlinks);

    $str .= "</table>\n";
    return $str;
}

/**
 * Convert the rows of data into html, including formatting datetime values and adding hotlinks
 * @param array $columns
 * @param array $fields
 * @param array $hotlinks_in
 * @return string
 */
function make_detail_table_data_rows(array $columns, array $fields, array $hotlinks_in): string {
    $str = "";
    $colIndex = 0;

    $datetimeColumns = array();
    $label_formatter = new \App\Libraries\Label_formatter();

    // Look for any datetime columns
    foreach ($columns as $column) {
        // mssql returns 'datetime', sqlsrv returns 93 (SQL datetime)
        if ($column->type == 'datetime' || $column->type == 93) {
            $datetimeColumns[] = $column->name;
        }

        // postgres driver: types 'timestamp' and 'timestamptz'
        // Could possibly add others (see Sql_postgre.php for type codes)
        if ($column->type == 1114 || $column->type == 1184) {
            $datetimeColumns[] = $column->name;
        }
    }

    // Show dates/times in the form: Dec 5 2016 5:44 PM
    $dateFormat = "M j Y g:i A";

    $pathCopyData = array();
    $pathCopyButtonCount = 0;

    // (usually) Capitalize ID, then replace underscores with spaces
    $hotlinks = array_change_key_case($hotlinks_in, CASE_LOWER);

    // Include the URL updater class
    $url_updater = new \App\Libraries\URL_updater();

    // Make a form field for each field in the field specs
    foreach ($fields as $fieldName => $fieldValue) {
        // Don't display columns that begin with a hash character
        if ($fieldName[0] == '#') {
            continue;
        }

        $labelFormatted = $label_formatter->format($fieldName);

        $hotlink_specs = get_hotlink_specs_for_field(strtolower($fieldName), strtolower($labelFormatted), $hotlinks);

        // Look for an entry in $hotlinks that matches either this field name,
        // or this field name preceded by one or more plus signs
        $fieldSpec = get_fieldspec_with_link_type($hotlink_specs, "no_display");
        if ($fieldSpec) {
            // Skip this column (since a hotlink of type no_display is defined)
            continue;
        }

        // Default field display for table
        $label = $fieldName;
        $val = $fieldValue;
        if (is_null($val))
        {
            $val = '';
        }

        if (!is_null($fieldValue) && in_array($fieldName, $datetimeColumns)) {
            // Convert original date string to date object
            // then convert that to the desired display format.
            $parsedDateTime = false;
            if (is_string($fieldValue)) {
                $parsedDateTime = strtotime($fieldValue);
            } else {
                $parsedDateTime = $fieldValue;
            }
            if ($parsedDateTime) {
                $val = date($dateFormat, $parsedDateTime);
            }
        }

        $label_display = "<td>$labelFormatted</td>\n";

        // We will append </td> below
        $val_display = "<td>$val";

        // Override default field display with hotlinks
        foreach ($hotlink_specs as $hotlink_spec) {
            if (array_key_exists("WhichArg", $hotlink_spec) && strlen($hotlink_spec["WhichArg"]) > 0) {
                $wa = $hotlink_spec["WhichArg"];
                $wa_fmt = $label_formatter->format($wa);
                $wa_defmt = $label_formatter->deformat($wa);
                if (array_key_exists($wa, $fields)) {
                    $link_id = $fields[$wa];
                } elseif (array_key_exists($wa_fmt, $fields)) {
                    // Target field for hot link not found; try using the display-formatted target field
                    $link_id = $fields[$wa_fmt];
                } elseif (array_key_exists($wa_defmt, $fields)) {
                    // Target field for hot link not found; try using the display-deformatted target field
                    $link_id = $fields[$wa_defmt];
                } else {
                    // TODO: Trigger a warning message of some kind?
                    // Return an invalid link id to not break the page entirely; it's harder to see that there's a problem, but much easier to see the exact cause
                    $link_id = "COLUMN_NAME_MISMATCH";
                }
            } else {
                $link_id = "";
            }

            if (is_null($link_id))
            {
                $link_id = '';
            }

            if ($hotlink_spec['Placement'] == 'labelCol') {
                // Place the hotlink on the field label
                // Display the display-formatted field name
                $label_display = make_detail_report_hotlink($url_updater, $hotlink_spec, $link_id, $colIndex, $fieldName, $labelFormatted, $val);
            } else {
                // Assume 'valueCol'
                // Place the hotlink on the field value
                // Display the value as-is
                $val = $url_updater->fix_link($val);
                $val_display = make_detail_report_hotlink($url_updater, $hotlink_spec, $link_id, $colIndex, $val, $val);
            }
        }

        // Open row in table
        $rowColor = alternator('ReportEvenRow', 'ReportOddRow');
        $str .= "<tr class='$rowColor' >\n";

        // Check whether the value points to a shared folder on a window server
        $charIndex = strpos($val, "\\\\");

        if ($charIndex !== false) {
            $pathCopyButtonCount++;

            // Note: Copy functionality is implemented in clipboard.min.js
            // More info at https://www.npmjs.com/package/clipboard-js
            // and at       https://github.com/lgarron/clipboard.js

            $buttonHtml = "<button id='copy-data-button$pathCopyButtonCount' class='copypath_btn'>Copy</button>";

            $val_display .= " " . $buttonHtml;

            $folderPath = str_replace("\\", "\\\\", substr($val, $charIndex));

            $pathCopyData[$pathCopyButtonCount] = $folderPath;
        }

        // First column in table is field name
        // Second column in table is field value, possibly with special formatting
        $str .= $label_display . $val_display . "</td>\n";

        // Close row in table
        $str .= "</tr>\n";

        $colIndex++;
    }

    if (sizeof($pathCopyData) > 0) {
        $scriptData = "\n<script>\n";    // Or "<p>\n";

        foreach ($pathCopyData as $key => $value) {
            // Attach code to the JQuery dialog's .on("click") method (synonymous with .click())
            $scriptData .= '$("#copy-data-button' . $key . '").on("click",function(e) {';
            $scriptData .= "    navigator.clipboard.writeText('$value'); ";
            $scriptData .= "    console.log('success: copy-data-button$key'); ";
            $scriptData .= "  });\n";

            /*
             * Alternative approach, using .getElementById
             * and a Javascript promise
             *
              $scriptData .= "document.getElementById('copy-data-button$key').addEventListener('click', function() {";
              $scriptData .= "  navigator.clipboard.write([new ClipboardItem({\n";
              $scriptData .= "    'text/plain': new Blob(['$value'], {type: 'text/plain'}),\n";
              // $scriptData .= "    'text/html': new Blob(['$value'], {type: 'text/html'})\n";
              $scriptData .= "  })]).then(\n";
              $scriptData .= "    function(){console.log('success'); },\n";
              $scriptData .= "    function(err){console.log('failure', err);\n";
              $scriptData .= "  });\n";
              $scriptData .= "});\n";
             */
        }

        $scriptData .= "</script>\n";    // Or "</p>\n";

        $str .= $scriptData;
    }

    return $str;
}

/**
 * Get hotlink info for the given field
 * @param string $fieldName Field name
 * @param string $fieldNameFormatted Field name, formatted for display
 * @param array $hotlinks hotlink info
 * @return array Array of hotlink info
 */
function get_hotlink_specs_for_field(string $fieldName, string $fieldNameFormatted, array $hotlinks): array {
    // List of any hotlink spec(s) for the field
    $hotlink_specs = array();

    // Is a primary hotlink defined for the field?
    if (array_key_exists($fieldName, $hotlinks)) {
        $hotlink_specs[] = $hotlinks[$fieldName];
    } else if (array_key_exists($fieldNameFormatted, $hotlinks)) {
        // Check the display-formatted name
        $hotlink_specs[] = $hotlinks[$fieldNameFormatted];
    }

    // Is a secondary hotlink defined for field?
    // Secondary keys have a plus sign ahead of the field name
    if (array_key_exists('+' . $fieldName, $hotlinks)) {
        $hotlink_specs[] = $hotlinks['+' . $fieldName];
    } else if (array_key_exists('+' . $fieldNameFormatted, $hotlinks)) {
        // Check the display-formatted name
        $hotlink_specs[] = $hotlinks['+' . $fieldNameFormatted];
    }

    return $hotlink_specs;
}

/**
 * Look for a hotlink of the given type; return it if found or null if not found
 * @param array $hotlinks hotlink info for a specific field
 * @param string $linkTypeName
 * @return string|null
 */
function get_fieldspec_with_link_type(array $hotlinks, string $linkTypeName) {
    // Look for a hotlink name that matches this field name
    foreach ($hotlinks as $hotlink_spec) {
        if ($hotlink_spec["LinkType"] == $linkTypeName) {
            return $hotlink_spec;
        }
    }

    return null;
}

/**
 * Construct a detail report hotlink
 * @param \App\Libraries\URL_updater $url_updater URL_updater instances
 * @param array  $colSpec  Key/value pairs from detail_report_hotlinks in the Model Config DB
 *                         LinkType, WhichArg, Target, Placement, id, and Options
 * @param string $link_id   Data value for field specified by WhichArg
 * @param int    $colIndex  Form field index (0-based)
 * @param string $text      Form field text
 * @param string $display   Form field displayed text
 * @param string $val     Data value for this form field from the database.
 *                        If Name and WhichArg are the same, $link_id and $val will be the same
 *                        For hotlinks that have 'valueCol' for the hotlink placement, $val will be an empty string
 * @return string
 */
function make_detail_report_hotlink(\App\Libraries\URL_updater $url_updater, array $colSpec, string $link_id, $colIndex, string $text, string $display, string $val = ''): string {

    // Include several helper methods

    // Description         | Helper File
    // --------------------|------------------------------------------
    // String operations   | app/Helpers/string_helper.php
    // Number formatting   | app/Helpers/number_formatting_helper.php
    // Wildcard conversion | app/Helpers/wildcard_conversion_helper.php

    helper(['string', 'number_formatting', 'wildcard_conversion']);

    $str = "";
    $fld_id = $colSpec["id"];
    $link_class = "";

    if ($link_id === "COLUMN_NAME_MISMATCH") {
        $link_class = " class=\"broken\"";
    }

    if (array_key_exists("WhichArg", $colSpec)) {
        $whichArg = $colSpec["WhichArg"];
    } else {
        $whichArg = "";
    }
    $type = $colSpec['LinkType'];
    $target = $colSpec['Target'];
    $options = $colSpec['Options'];
    $cell_class = "";

    switch ($type) {
        case "detail-report":
            // Link to another DMS page, including both list reports and detail reports
            if (is_array($options) && !empty($options) && array_key_exists('HideLinkIfValueMatch', $options)) {
                $hideLinkMatchText = $options['HideLinkIfValueMatch'];
                if (empty($val) && $link_id === $hideLinkMatchText ||
                        !empty($val) && $val === $hideLinkMatchText) {
                    $str = $display;
                    break;
                }
            }

            if (!empty($options) && array_key_exists('MaskLink', $options)) {
                $maskLinkText = $options['MaskLink'];
                if (!empty($maskLinkText)) {
                    $display = $maskLinkText;
                }
            }

            // Replace %, [, and ] in $link_id with placeholders
            $link_id = encode_special_values($link_id);

            $url = make_detail_report_url($target, $link_id, $options);
            $str = "<a$link_class id='lnk_{$fld_id}' href='$url'>$display</a>";
            break;

        case "href-folder":
            if ($val) {
                $lnk = ltrim(str_replace('\\', '/', $val), '/'); // remove leading '/'
                $str = "<a href='http://$lnk'>$display</a>";
            } else {
                $str = $display;
            }
            break;

        case "literal_link":
            // Link to the URL specified by $text
            // The link text is the target URL
            $str .= "<a href='$text' target='External$colIndex'>$text</a>";
            break;

        case "masked_link":
            // Link to the URL specified by $text
            // The link text is specified by the label setting in Options, for example {"Label":"Show files"}
            if ($text) {
                $lbl = "(label is not defined)";
                if (!empty($options) && array_key_exists('Label', $options)) {
                    $lbl = $options['Label'];
                }
                $str .= "<a href='$text' target='External$colIndex'>$lbl</a>";
            } else {
                $str .= "";
            }
            break;

        case "masked_link_list":
            // Link to each URL listed in a semicolon or comma-separated list of items in $text
            // The link text is specified by the label setting in Options, for example {"Label":"Show files"}
            // If the Label setting is the keyword UrlSegment#, for the link text use the given segment from the URL
            // For example, if Label is UrlSegment4, and the URL is https://status.my.emsl.pnl.gov/view/t/337916
            // the link text will be 337916
            $matches = array();

            // Determine the delimiter by looking for the first comma or semicolon
            $delim = (preg_match('/[,;]/', $text, $matches)) ? $matches[0] : '';
            $flds = ($delim == '') ? array($text) : explode($delim, $text);

            $lbl = "";
            $urlSegmentForLabel = 0;
            if (!empty($options) && array_key_exists('Label', $options)) {
                $lbl = $options['Label'];

                $segmentMatches = array();
                $urlSegmentForLabel = (preg_match('/UrlSegment([0-9]+)/i', $lbl, $segmentMatches)) ? $segmentMatches[1] : 0;

                if ($urlSegmentForLabel > 0) {
                    $lbl = '';
                }
            }

            $links = array();
            foreach ($flds as $targetUrl) {
                $targetUrl = trim($targetUrl);

                $lblToUse = '';
                if ($urlSegmentForLabel > 0) {
                    // Split the URL on forward slashes
                    // Example contents of $urlParts for https://status.my.emsl.pnl.gov/view/t/337916
                    // $urlParts[0] = https:
                    // $urlParts[1] =
                    // $urlParts[2] = status.my.emsl.pnl.gov
                    // $urlParts[3] = view
                    // $urlParts[4] = t
                    // $urlParts[5] = 337916

                    $urlParts = explode('/', $targetUrl);
                    if (count($urlParts) > $urlSegmentForLabel + 1) {
                        $lblToUse = $urlParts[$urlSegmentForLabel + 1];
                    } else {
                        $lblToUse = $lbl;
                    }
                } else {
                    $lblToUse = $lbl;
                }

                if (empty($lblToUse)) {
                    $lblToUse = $targetUrl;
                }

                $links[] = "<a href='$targetUrl' target='External$colIndex'>$lblToUse</a>";
            }
            $str .= implode($delim . ' ', $links);
            break;

        case "item_list":
            // $f is a vertical bar separated list
            // Create a one-row table using the items in the list
            // Look for item Widths in the Options field, for example:
            // {"Widths":"20,80"}   or
            // {"Widths":"20%,80%"}
            // This indicates to use column widths of 20% and 80%
            $colWidthList = getOptionValue($colSpec, 'Widths', '');
            $colWidths = explode(',', $colWidthList);

            $str .= "<table class='item_list_table' width='100%'><tr>";
            $i = 0;
            foreach (explode('|', $text) as $f) {
                if ($i < count($colWidths)) {
                    $widthText = trim($colWidths[$i]);
                    if (substr($widthText, -1) == '%') {
                        // Remove the trailing percent sign
                        $widthText = substr($widthText, 0, strlen($widthText) - 1);
                    }
                } else {
                    $widthText = '';
                }

                $widthValue = filter_var($widthText, FILTER_VALIDATE_INT);
                if ($widthValue !== false) {
                    $str .= "<td width='$widthValue%'>" . trim($f) . '</td>';
                } else {
                    $str .= '<td>' . trim($f) . '</td>';
                }

                $i++;
            }
            $str .= "</tr></table>";
            break;

        case "link_list":
            // Create a separate hotlink for each item in a semicolon or comma-separated list of items in $text
            // The link to use is defined by the target column in the detail_report_hotlinks section of the config DB
            $matches = array();

            // Determine the delimiter by looking for the first comma or semicolon
            $delim = (preg_match('/[,;]/', $text, $matches)) ? $matches[0] : '';
            $flds = ($delim == '') ? array($text) : explode($delim, $text);

            if (!empty($options) && array_key_exists('HideLinkIfValueMatch', $options)) {
                $hideLinkMatchText = $options['HideLinkIfValueMatch'];
            } else {
                $hideLinkMatchText = '';
            }

            $links = array();
            foreach ($flds as $currentItem) {
                $currentItem = trim($currentItem);
                if (empty($currentItem)) {
                    continue;
                }
                if (!empty($hideLinkMatchText) && $currentItem === $hideLinkMatchText) {
                    $links[] = $currentItem;
                    continue;
                }

                $renderHTTP = true;
                $url = make_detail_report_url($target, $currentItem, $options, $renderHTTP);
                $links[] = "<a href='$url'>$currentItem</a>";
            }
            $str .= implode($delim . ' ', $links);
            break;

        case "link_table":
            // Table with links
            $str .= "<table class='inner_table'>";
            foreach (explode(',', $text) as $currentItem) {
                $currentItem = trim($currentItem);
                $renderHTTP = true;
                $url = make_detail_report_url($target, $currentItem, $options, $renderHTTP);
                $str .= "<tr><td><a href='$url'>$currentItem</a></td></tr>";
            }
            $str .= "</table>";
            break;

        case "tablular_list":
        case "tabular_list":
            // Parse data separated by colons and vertical bars and create a table
            // Row1_Name:Row1_Value|Row2_Name:Row2_Value|
            $str .= "<table class='inner_table'>";
            foreach (explode('|', $text) as $currentItem) {
                $str .= '<tr>';
                foreach (explode(':', $currentItem) as $itemField) {
                    $str .= '<td>' . trim($itemField) . '</td>';
                }
                $str .= '</tr>';
            }
            $str .= "</table>";
            break;

        case "tabular_link_list":
            // Parse data separated by colons and vertical bars and create a table
            // Values in the second column are linked to the page defined by the target column in the detail_report_hotlinks section of the config DB
            // If the data starts with !Headers!, this means the first set of colon separated words are header names
            //
            // Example 1:
            // Row1_Name:Row1_Value|Row2_Name:Row2_Value|
            // Row1_Value will link to the given target
            //
            // Example 2:
            // !Headers!Channel:Exp_ID:Experiment:Channel Type|1:212457:SampleA:Normal|2:212458:SampleB:Normal|3:212459:SampleC:Normal|4:212460:SampleD:Reference
            // The headers are Channel, Exp_ID, Experiment, and Channel Type
            // The numbers 212457, 212458, 212459, and 212460 will link to a URL like https://dms2.pnl.gov/experimentid/show/212457

            $str .= "<table class='inner_table'>";
            $headerCount = 0;
            foreach (explode('|', $text) as $currentItem) {
                if (StartsWith(strtolower($currentItem), '!headers!')) {
                    $colTag = 'th';
                    $currentItem = substr($currentItem, strlen('!headers!'));
                    $headerLine = true;
                } else {
                    $colTag = 'td';
                    $headerLine = false;
                }
                $str .= '<tr>';

                $rowColNum = 0;
                if ($headerCount > 0) {
                    $explodeLimit = $headerCount;
                } else {
                    $explodeLimit = PHP_INT_MAX;
                }

                foreach (explode(':', $currentItem, $explodeLimit) as $itemField) {
                    $rowColNum += 1;
                    $trimmedValue = trim($itemField);

                    if ($headerLine === false && $rowColNum == 2) {
                        // This is the second column, and it's not the header row
                        // Render as a URL
                        $renderHTTP = true;
                        $url = make_detail_report_url($target, $trimmedValue, $options, $renderHTTP);
                        $str .= "<$colTag><a href='$url'>$trimmedValue</a></$colTag>";
                    } else {
                        if ($headerLine) {
                            $headerCount++;
                        }
                        $str .= "<$colTag>$trimmedValue</$colTag>";
                    }
                }

                $str .= '</tr>';
            }
            $str .= "</table>";

            break;

        case "color_label":
            $cx = "";
            if (!empty($options) && array_key_exists($link_id, $options)) {
                $cx = "class='" . $options[$link_id] . "' style='padding: 1px 5px 1px 5px;'";
            }
            $str .= "<span $cx>$display</span>";
            break;

        case "doi_link":
            $linkOrValue = $url_updater->get_doi_link($text, $colIndex);
            $str .= $linkOrValue;
            break;

        case "format_commas":
            $str = valueToString($text, $colSpec, true);
            break;

        case "xml_params":
            $str .= make_table_from_param_xml($text);
            break;

        case "markup":
            // Replace newlines with <br> using nl2br
            $str .= nl2br($text);
            break;

        case "monomarkup":
            // Replace newlines with <br> using nl2br
            // Also surround the entire block with <code></code>
            // CSS formatting in base.css renders the text as monospace; see table.DRep pre
            $str .= '<code>' . nl2br($text) . '</code>';
            break;

        case "glossary_entry":
            $url = make_detail_report_url($target, $whichArg, $options);

            if (!empty($options) && array_key_exists('Label', $options)) {
                $linkTitle = "title='" . $options['Label'] . "'";
            } else {
                $linkTitle = "";
            }

            $str = "<a id='lnk_{$fld_id}' target='_GlossaryEntry' " . $linkTitle . " href='$url'>$text</a>";

            // Pop-up option
            // $str = "<a id='lnk_{$fld_id}' target='popup' href='$url'  onclick=\"window.open('$url','$text','width=800,height=600')\">$text</a>";
            break;

        case "no_display":
            // Hide no_display fields
            return "";

        default:
            $str = "??? $text ???";
            break;
    }

    // The calling method will append </td>
    return "<td $cell_class>$str";
}

/**
 * Make a table given XML
 * @param string $xml
 * @return string
 */
function make_table_from_param_xml(string $xml): string {
    $dom = new \DOMDocument();
    $dom->loadXML('<root>' . $xml . '</root>');
    $xp = new \DOMXPath($dom);
    $params = $xp->query("//Param");

    $s = '';
    $s .= "<table class='inner_table'>\n";
    $cur_section = '';
    foreach ($params as $param) {
        if (get_class($param) != 'DOMElement') {
            continue;
        }
        $name = $param->getAttribute('Name');
        $value = $param->getAttribute('Value');
        $section = $param->getAttribute('Section');
        if ($section != $cur_section) {
            $cur_section = $section;
            $s .= "<tr><td colspan='2'><span style='font-weight:bold;'>$section</span></td></tr>\n";
        }
        $s .= "<tr><td>$name</td><td>$value</td></tr>\n";
    }
    $s .= "</table>\n";
    return $s;
}

/**
 * Create HTML to display detail report edit links
 * @param string $controller_name
 * @param mixed $id
 * @param bool $show_create_links
 * @return string
 */
function make_detail_report_edit_links(string $controller_name, $id, bool $show_create_links): string {
    $str = '';
    $edit_url = site_url("$controller_name/edit/$id");
    $copy_url = site_url("$controller_name/create/$id");
    $new_url = site_url("$controller_name/create");

    $str .= "<span><a id='btn_goto_edit_main' class='button' title='Edit this item' href='$edit_url'>Edit</a></span>";
    if ($show_create_links) {
        $str .= "<span><a id='btn_goto_copy_main' class='button'   title='Copy this record' href='$copy_url'>Copy</a></span>";
        $str .= "<span><a id='btn_goto_create_main' class='button' title='Make new record'  href='$new_url'>New</a></span>";
    } else {
        $str .= "<span><a id='btn_goto_copy_main' class='button-disabled'   title='Item copy is disabled' href='#'>Copy</a></span>";
        $str .= "<span><a id='btn_goto_create_main' class='button-disabled' title='Item creation is disabled' href='#'>New</a></span>";
    }

    return $str;
}

/**
 * Create HTML to display detail report aux info section
 * @param array $result
 * @return string
 */
function make_detail_report_aux_info_section(array $result): string {
    $str = '';
    $str .= "<table class='DRep'>\n";
    $str .= "<tr>";
    $str .= "<th>Category</th>";
    $str .= "<th>Subcategory</th>";
    $str .= "<th>Item</th>";
    $str .= "<th>Value</th>";
    $str .= "</tr>\n";
    foreach ($result as $row) {
        $rowColor = alternator('ReportEvenRow', 'ReportOddRow');
        $str .= "<tr class='$rowColor' >\n";
        $str .= "<td>" . $row['category'] . "</td>";
        $str .= "<td>" . $row['subcategory'] . "</td>";
        $str .= "<td>" . $row['item'] . "</td>";
        $str .= "<td>" . $row['value'] . "</td>";
        $str .= "</tr>\n";
    }
    $str .= "</table>\n";
    return $str;
}

/**
 * Create HTML for controls for displaying and editing aux info on detail report page
 * @param string $aux_info_target
 * @param mixed $aux_info_id
 * @param mixed $id
 * @return string
 */
function make_detail_report_aux_info_controls(string $aux_info_target, $aux_info_id, $id): string {
    $js = "javascript:showAuxInfo(\"aux_info_container\", \"" . site_url("aux_info/show/" . $aux_info_target . "/" . $aux_info_id) . "\")";
    $str = '';
    $str .= "Aux Info: |";
    $str .= "<span>";
    $str .= "<a href='$js'>Show...</a>";
    $str .= "</span>|";
    $str .= "<span>";
    $str .= "<a href='" . site_url("aux_info/entry/" . $aux_info_target . "/" . $aux_info_id . "/" . $id) . "'>Edit...</a>";
    $str .= "</span>|";
    return $str;
}

/**
 * Create HTML to display detail report commands section
 * @param array $commands
 * @param string $tag
 * @param mixed $id
 * @return string
 */
function make_detail_report_commands(array $commands, string $tag, $id): string {
    $cmds = array();
    foreach ($commands as $label => $spec) {
        $target = $spec['Target'];
        $cmd = $spec['Command'];
        $tooltip = $spec['Tooltip'];

        // Message to show the user to confirm the action
        $prompt = $spec['Prompt'];
        if (empty($prompt)) {
            $prompt = 'Are you sure that you want to update the database?';
        }

        switch ($spec['Type']) {
            case 'copy_from':
                $url = site_url($target . "/create/$tag/" . $id);
                $icon = cmd_link_icon("go");
                $cmds[] = "<a class='cmd_link_a' href='$url' title='$tooltip'>$label $icon</a>";
                break;
            case 'call':
                $url = site_url($target . "/$cmd/" . $id);
                $icon = cmd_link_icon("go");
                $cmds[] = "<a class='cmd_link_a' href='$url' title='$tooltip'>$label $icon</a>";
                break;
            case 'cmd_op':
                $url = site_url($target . "/command");
                $icon = cmd_link_icon();
                $cmds[] = "<a class='cmd_link_a' href='javascript:detRep.performCommand(\"$url\", \"$id\", \"$cmd\", \"$prompt\")' title='$tooltip'>$label $icon</a>";
                break;
        }
    }
    $str = "";
    foreach ($cmds as $cmd) {
        $str .= "<span class='cmd_link_cartouche'>$cmd</span>\n";
    }
    return $str;
}

/**
 * Construct a URL to include as a hotlink on a detail report page
 * @param string $target      Target page family, optionally including filters, e.g. 'param_file/report/-/~'
 * @param string $link_id     Data value for field specified by WhichArg
 * @param array|null $options     Link processing options
 * @param bool $renderHTTP  If true, and if $link_id starts with http, simply link to that URL
 * @return string
 */
function make_detail_report_url(string $target, string $link_id, ?array $options, bool $renderHTTP = false): string {

    if ($renderHTTP && strncasecmp($link_id, "http", 4) == 0) {
        // The field has a URL; link to it
        $url = $link_id;
    } else {

        // Insert an at sign (@) if it is not already present
        // When populating $url in this method, we will replace the @ sign in $target with $link_id

        if (strpos($target, '@') === false) {
            // Need to add the @ sign

            // If $target does not end in ~, add /
            $sep = (substr($target, -1) == '~') ? '' : '/';
            $targetNew = $target . $sep . '@';
        } else {
            $targetNew = $target;
        }

        if (is_array($options) && !empty($options) && array_key_exists('RemoveRegEx', $options)) {
            $pattern = $options['RemoveRegEx'];
            if (!empty($pattern)) {
                // Replace %2C with a comma
                // Replace %20 with a space
                // Replace %28 with (
                // Replace %29 with )
                $link_id_updated = preg_replace("/\%2C/", ",",
                                   preg_replace("/\%20/", " ",
                                   preg_replace("/\%28/", "(",
                                   preg_replace("/\%29/", ")", $link_id))));

                $pattern = '/' . $pattern . '/';
                $link_id = preg_replace($pattern, "", $link_id_updated);
            }
        }

        $url = reduce_double_slashes(site_url(str_replace('@', $link_id, $targetNew)));
    }

    return $url;
}

/**
 * Make links for exporting data
 * @param string $entity
 * @param mixed $id
 * @return string
 */
function make_export_links(string $entity, $id): string {
    // Example URLs:
    // http://dms2.pnl.gov/experiment/export_detail/QC_Shew_16_01/excel
    // http://dms2.pnl.gov/experiment/export_detail/QC_Shew_16_01/tsv
    // http://dms2.pnl.gov/experiment/export_spreadsheet/QC_Shew_16_01/data/true/xlsx
    $s = '';
    $excel_lnk = site_url($entity . "/export_detail/" . $id . "/excel");
    $tsv_lnk = site_url($entity . "/export_detail/" . $id . "/tsv");
    $spreadsheet_lnk = site_url($entity . "/export_spreadsheet/" . $id . "/data/true/xlsx");

    $s .= "Download in other formats: ";
    $s .= "|<span><a href='$excel_lnk'>Excel</a></span>";
    $s .= "|<span><a href='$tsv_lnk'>Tab-Delimited Text</a></span>";
    $s .= "|<span><a href='$spreadsheet_lnk'>Spreadsheet Template</a></span>";
    $s .= "|";

    return $s;
}

/**
 * Construct a message box
 * @param string $message
 * @return string
 */
function make_message_box(string $message): string {
    //$style_sheet = base_url('css/base.css');
    $class = (strripos($message, 'error') === false) ? 'EPag_message' : 'EPag_error';
    $s = '';
    $s .= "<div class='$class' style='width:40em;margin:20px;'>";
    $s .= $message;
    $s .= "</div>";
    return $s;
}
