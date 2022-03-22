<?php

/**
 * Create HTML to display detail report fields, including adding hotlinks
 * Also adds the header fields, including the link to the list report and edit/entry buttons
 * This method is called from view app/Views/main/detail_report_data.php
 * That view is loaded from method show_data in DmsBase.php
 * @param type $columns
 * @param type $fields
 * @param type $hotlinks
 * @param type $controller_name
 * @param type $id
 * @param type $show_entry_links
 * @param type $show_create_links
 * @return string
 */
function make_detail_report_section($columns, $fields, $hotlinks, $controller_name, $id, $show_entry_links, $show_create_links) {
    $str = '';
    // fields are contained in a table
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
 * @param type $columns
 * @param type $fields
 * @param type $hotlinks
 * @return string
 */
function make_detail_table_data_rows($columns, $fields, $hotlinks) {
    $str = "";
    $colIndex = 0;

    $datetimeColumns = array();

    // Look for any datetime columns
    foreach ($columns as $column) {
        // mssql returns 'datetime', sqlsrv returns 93 (SQL datetime)
        if ($column->type == 'datetime' || $column->type == 93) {
            $datetimeColumns[] = $column->name;
        }
    }

    // Show dates/times in the form: Dec 5 2016 5:44 PM
    $dateFormat = "M j Y g:i A";

    $pathCopyData = array();
    $pathCopyButtonCount = 0;


    // Include the URL updater class
    $url_updater = new \App\Libraries\URL_updater();

    // make a form field for each field in the field specs
    foreach ($fields as $fieldName => $fieldValue) {
        // don't display columns that begin with a hash character
        if ($fieldName[0] == '#') {
            continue;
        }

        // default field display for table
        $label = $fieldName;
        $val = $fieldValue;

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

        $label_display = "<td>$label</td>\n";

        // We will append </td> below
        $val_display = "<td>$val";

        // override default field display with hotlinks
        $hotlink_specs = get_hotlink_specs_for_field($fieldName, $hotlinks);
        foreach ($hotlink_specs as $hotlink_spec) {
            if (array_key_exists("WhichArg", $hotlink_spec) && strlen($hotlink_spec["WhichArg"]) > 0) {
                $link_id = $fields[$hotlink_spec["WhichArg"]];
            } else {
                $link_id = "";
            }

            if ($hotlink_spec['Placement'] == 'labelCol') {
                $label_display = make_detail_report_hotlink($url_updater, $hotlink_spec, $link_id, $colIndex, $fieldName, $val);
            } else {
                $val = $url_updater->fix_link($val);
                $val_display = make_detail_report_hotlink($url_updater, $hotlink_spec, $link_id, $colIndex, $val);
            }
        }

        // open row in table
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

        // first column in table is field name
        // second column in table is field value, possibly with special formatting
        $str .= $label_display . $val_display . "</td>\n";

        // close row in table
        $str .= "</tr>\n";

        $colIndex++;
    }

    if (sizeof($pathCopyData) > 0) {
        $scriptData = "\n<script>\n";    // Or "<p>\n";

        foreach ($pathCopyData as $key => $value) {
            // Attach code to the JQuery dialog's .on("click") method (synonymous with .click())
            $scriptData .= '$("#copy-data-button' . $key . '").on("click",function(e) {';
            $scriptData .= "    clipboard.writeText('$value'); ";
            $scriptData .= "    console.log('success: copy-data-button$key'); ";
            $scriptData .= "  });\n";

            /*
             * Alternative approach, using .getElementById
             * and a Javascript promise
             *
              $scriptData .= "document.getElementById('copy-data-button$key').addEventListener('click', function() {";
              $scriptData .= "  clipboard.write({\n";
              $scriptData .= "    'text/plain': '$value',\n";
              // $scriptData .= "    'text/html': '$value'\n";
              $scriptData .= "  }).then(\n";
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
 * @param type $fieldName Field name
 * @param type $hotlinks hotlink info
 * @return type Array of hotlink info
 */
function get_hotlink_specs_for_field($fieldName, $hotlinks) {
    // List of any hotlink spec(s) for the field
    $hotlink_specs = array();

    // Is a primary hotlink defined for the field?
    if (array_key_exists($fieldName, $hotlinks)) {
        $hotlink_specs[] = $hotlinks[$fieldName];
    }

    // Is a secondary hotlink defined for field?
    // Secondary keys have a plus sign ahead of the field name
    if (array_key_exists('+' . $fieldName, $hotlinks)) {
        $hotlink_specs[] = $hotlinks['+' . $fieldName];
    }

    return $hotlink_specs;
}

/**
 * Construct a detail report hotlink
 * @param array $url_updater URL_updater instances
 * @param array $colSpec  Key/value pairs from detail_report_hotlinks in the Model Config DB
 *                        LinkType, WhichArg, Target, Placement, id, and Options
 * @param type $link_id   Data value for field specified by WhichArg
 * @param type $colIndex  Form field index (0-based)
 * @param type $display   Form field name
 * @param type $val       Data value for this form field from the database.
 *                        If Name and WhichArg are the same, $link_id and $val will be the same
 * @return type
 */
function make_detail_report_hotlink($url_updater, $colSpec, $link_id, $colIndex, $display, $val = '') {

    // Include the String operations methods
    // Include the Number formatting methods
    helper(['string', 'number_formatting']);

    $str = "";
    $fld_id = $colSpec["id"];

    if (array_key_exists("WhichArg", $colSpec)) {
        $wa = $colSpec["WhichArg"];
    } else {
        $wa = "";
    }
    $type = $colSpec['LinkType'];
    $target = $colSpec['Target'];
    $options = $colSpec['Options'];
    $cell_class = "";

    switch ($type) {
        case "detail-report":
            // Link to another DMS page, including both list reports and detail reports
            if (!empty($options) && array_key_exists('HideLinkIfValueMatch', $options)) {
                $hideLinkMatchText = $options['HideLinkIfValueMatch'];
                if (empty($val) && $link_id === $hideLinkMatchText ||
                        !empty($val) && $val === $hideLinkMatchText) {
                    $str = $display;
                    break;
                }
            }

            $url = make_detail_report_url($target, $link_id, $options);
            $str = "<a id='lnk_${fld_id}' href='$url'>$display</a>";
            break;

        case "href-folder":
            if ($val) {
                $lnk = str_replace('\\', '/', $val);
                $str = "<a href='file:///$lnk'>$display</a>";
            } else {
                $str = $display;
            }
            break;

        case "literal_link":
            // Link to the URL specified by $display
            // The link text is the target URL
            $str .= "<a href='$display' target='External$colIndex'>$display</a>";
            break;

        case "masked_link":
            // Link to the URL specified by $display
            // The link text is specified by the label setting in Options, for example {"Label":"Show files"}
            if ($display) {
                $lbl = "(label is not defined)";
                if (!empty($options) && array_key_exists('Label', $options)) {
                    $lbl = $options['Label'];
                }
                $str .= "<a href='$display' target='External$colIndex'>$lbl</a>";
            } else {
                $str .= "";
            }
            break;

        case "masked_link_list":
            // Link to each URL listed in a semicolon or comma-separated list of items in $display
            // The link text is specified by the label setting in Options, for example {"Label":"Show files"}
            // If the Label setting is the keyword UrlSegment#, for the link text use the given segment from the URL
            // For example, if Label is UrlSegment4, and the URL is https://status.my.emsl.pnl.gov/view/t/337916
            // the link text will be 337916
            $matches = array();

            // Determine the delimiter by looking for the first comma or semicolon
            $delim = (preg_match('/[,;]/', $display, $matches)) ? $matches[0] : '';
            $flds = ($delim == '') ? array($display) : explode($delim, $display);

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
            foreach (explode('|', $display) as $f) {
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
            // Create a separate hotlink for each item in a semicolon or comma-separated list of items in $display
            // The link to use is defined by the target column in the detail_report_hotlinks section of the config DB
            $matches = array();

            // Determine the delimiter by looking for the first comma or semicolon
            $delim = (preg_match('/[,;]/', $display, $matches)) ? $matches[0] : '';
            $flds = ($delim == '') ? array($display) : explode($delim, $display);

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
            foreach (explode(',', $display) as $currentItem) {
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
            foreach (explode('|', $display) as $currentItem) {
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
            foreach (explode('|', $display) as $currentItem) {
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
            $linkOrValue = $url_updater->get_doi_link($display, $colIndex);
            $str .= $linkOrValue;
            break;

        case "format_commas":
            $str = valueToString($display, $colSpec, true);
            break;

        case "xml_params":
            $str .= make_table_from_param_xml($display);
            break;

        case "markup":
            // Replace newlines with <br> using nl2br
            $str .= nl2br($display);
            break;

        case "monomarkup":
            // Replace newlines with <br> using nl2br
            // Also surround the entire block with <code></code>
            // CSS formatting in base.css renders the text as monospace; see table.DRep pre
            $str .= '<code>' . nl2br($display) . '</code>';
            break;

        case "glossary_entry":
            $url = make_detail_report_url($target, $wa, $options);

            if (!empty($options) && array_key_exists('Label', $options)) {
                $linkTitle = "title='" . $options['Label'] . "'";
            } else {
                $linkTitle = "";
            }

            $str = "<a id='lnk_${fld_id}' target='_GlossaryEntry' " . $linkTitle . " href='$url'>$display</a>";

            // Pop-up option
            // $str = "<a id='lnk_${fld_id}' target='popup' href='$url'  onclick=\"window.open('$url','$display','width=800,height=600')\">$display</a>";
            break;

        default:
            $str = "??? $display ???";
            break;
    }

    // The calling method will append </td>
    return "<td $cell_class>$str";
}

/**
 * Make a table given XML
 * @param type $xml
 * @return string
 */
function make_table_from_param_xml($xml) {
    $dom = new \DOMDocument();
    $dom->loadXML('<root>' . $xml . '</root>');
    $xp = new \DOMXPath($dom);
    $params = $xp->query("//Param");

    $s = '';
    $s .= "<table class='inner_table'>\n";
    $cur_section = '';
    foreach ($params as $param) {
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
 * @param type $controller_name
 * @param type $id
 * @param type $show_create_links
 * @return type
 */
function make_detail_report_edit_links($controller_name, $id, $show_create_links) {
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
 * @param type $result
 * @return string
 */
function make_detail_report_aux_info_section($result) {
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
        $str .= "<td>" . $row['Category'] . "</td>";
        $str .= "<td>" . $row['Subcategory'] . "</td>";
        $str .= "<td>" . $row['Item'] . "</td>";
        $str .= "<td>" . $row['Value'] . "</td>";
        $str .= "</tr>\n";
    }
    $str .= "</table>\n";
    return $str;
}

/**
 * Create HTML for controls for displaying and editing aux info on detail report page
 * @param type $aux_info_target
 * @param type $aux_info_id
 * @param type $id
 * @return string
 */
function make_detail_report_aux_info_controls($aux_info_target, $aux_info_id, $id) {
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
 * @param type $commands
 * @param type $tag
 * @param type $id
 * @return type
 */
function make_detail_report_commands($commands, $tag, $id) {
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
                $cmds[] = "<a class='cmd_link_a' href='javascript:delta.performCommand(\"$url\", \"$id\", \"$cmd\", \"$prompt\")' title='$tooltip'>$label $icon</a>";
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
 * @param type $target
 * @param type $link_id
 * @param type $options
 * @param type $renderHTTP
 * @return type
 */
function make_detail_report_url($target, $link_id, $options, $renderHTTP = false) {

    if ($renderHTTP && strncasecmp($link_id, "http", 4) == 0) {
        // The field has a URL; link to it
        $url = $link_id;
    } else {

        // Insert an @ sign if it is not already present
        // When constructing the URL, we will replace the @ sign in $target with $link_id
        if (strpos($target, '@') === false) {
            // Need to add the @ sign
            // If $target does not end in ~, then add /
            $sep = (substr($target, -1) == '~') ? '' : '/';
            $targetNew = $target . $sep . '@';
        } else {
            $targetNew = $target;
        }

        if (!empty($options) && array_key_exists('RemoveRegEx', $options)) {
            $pattern = $options['RemoveRegEx'];
            if (!empty($pattern)) {
                $pattern = '/' . $pattern . '/';
                $link_id = preg_replace($pattern, "", $link_id);
            }
        }

        $url = reduce_double_slashes(site_url(str_replace('@', $link_id, $targetNew)));
    }

    return $url;
}

/**
 * Make links for exporting data
 * @param type $entity
 * @param type $id
 * @return string
 */
function make_export_links($entity, $id) {
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
 * @param type $message
 * @return string
 */
function make_message_box($message) {
    //$style_sheet = base_url('css/base.css');
    $class = (strripos($message, 'error') === false) ? 'EPag_message' : 'EPag_error';
    $s = '';
    $s .= "<div class='$class' style='width:40em;margin:20px;'>";
    $s .= $message;
    $s .= "</div>";
    return $s;
}
