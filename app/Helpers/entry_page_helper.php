<?php

//
// Support functions for entry page features of DmsBase
//

/**
 * Get initial values for entry fields - URL will tell us where to get them
 * - defaults from config db
 * - current values from same entity type being edited
 * - current values from other entity type (if config db provides column mapping spec)
 * - URL segment values
 * @param array $segs
 * @param string $config_source
 * @param array $form_field_names
 * @param \App\Controllers\BaseController $controller
 * @return array
 */
function get_initial_values_for_entry_fields(array $segs, string $config_source, array $form_field_names, \App\Controllers\BaseController $controller): array {
    $initial_field_values = array();

    $num_segs = count($segs);
    //
    if ($num_segs <= 0) {
        // Just accept defaults
    } else if ($num_segs == 1) {
        // Get values from database using source and id that we were given
        $id = $segs[0];
        $input_model = $controller->getModel('Q_model', 'entry_page', $config_source);
        $initial_field_values = $input_model->get_item($id, $controller);
    } else {
        // Get values from an external source
        $source = $segs[0];
        $id = $segs[1];
        if ($source == 'init') {
            $segs = array_slice($segs, 1);
            // Get values from url segments:
            $initial_field_values = get_values_from_segs($form_field_names, $segs);
        } else if ($id == 'post') {
            // Get external source mapping
            $col_mapping = $controller->form_model->get_external_source_field_map($source);
            if ($col_mapping) {
                // Get values from POST data
                $request = \Config\Services::request();
                $postData = $request->getPost();
                $initial_field_values = load_from_external_source($col_mapping, $postData);
            }
        } else {
            // Get external source mapping
            $col_mapping = $controller->form_model->get_external_source_field_map($source);
            if ($col_mapping) {
                // Get values from database using source and id plucked from url
                $input_model = $controller->getModel('Q_model', 'detail_report', $source);
                $source_data = $input_model->get_item($id, $controller);
                $initial_field_values = load_from_external_source($col_mapping, $source_data);
            }
        }
    }
    return $initial_field_values;
}

/**
 * Return an array (of field => value) containing fields defined
 * in $col_mapping with values according to type of mapping defined
 * @param array $col_mapping
 * @param array $source_data
 * @return array
 */
function load_from_external_source(array $col_mapping, array $source_data): array {
    $a = array();
    $label_formatter = new \App\Libraries\Label_formatter();
    $source_data2 = array_change_key_case($source_data, CASE_LOWER);

    // Load entry fields from external source
    foreach ($col_mapping as $fld => $spec) {

        // $spec['type'] will typically be ColName, PostName, or Literal
        // However, it might have a suffix in the form '.action.ActionName'

        // Method get_entry_form_definitions() E_model.php looks for text
        // in the form 'ColName.action.ActionName'
        // and will add an 'action' item to the field spec

        switch ($spec['type']) {
            case 'ColName':
                // Copy the text in the specified column of the detail report for the source page family
                //  . " (field = " . $spec['value'] . ", action = $action)"
                $col = $spec['value'];
                $col_fmt = $label_formatter->format($col);
                $col_defmt = $label_formatter->deformat($col);
                $val = "";
                if (array_key_exists($col, $source_data)) {
                    $val = $source_data[$col];
                } elseif (array_key_exists($col_fmt, $source_data)) {
                    // Target column for column name not found; try using the display-formatted target field
                    $val = $source_data[$col_fmt];
                } elseif (array_key_exists($col_defmt, $source_data)) {
                    // Target column for column name not found; try using the display-deformatted target field
                    $val = $source_data[$col_defmt];
                } else {
                    // TODO: Trigger a warning message of some kind?
                    // Return an invalid link id to not break the page entirely; it's harder to see that there's a problem, but much easier to see the exact cause
                    $val = "COLUMN_NAME_MISMATCH";
                }
                $a[$fld] = $val;
                break;

            case 'PostName':
                // Retrieve the named POST value
                //$request = \Config\Services::request();
                //$pv = $request->getPost($spec['value']);
                $col = $spec['value'];
                $pv = "";
                if (array_key_exists($col, $source_data2)) {
                    $pv = $source_data2[$col];
                }
                $a[$fld] = $pv;
                break;

            case 'Literal':
                // Store a literal string
                $a[$fld] = $spec['value'];
                break;
        }

        // Any further actions?
        if (isset($spec['action'])) {
            switch ($spec['action']) {
                case 'ExtractUsername':
                    // Look for username in text of the form "Person Name (Username)"
                    // This Regex matches any text between two parentheses

                    $patUsername = '/\(([^)]+)\)/i';
                    $matches = array();

                    preg_match_all($patUsername, $a[$fld], $matches);

                    // The $matches array returned by preg_match_all is a 2D array
                    // Any matches to the first capture are in the array at $matches[0]

                    if (count($matches[1]) > 0) {
                        // Update $a[$fld] to be the last match found (excluding the parentheses)

                        $a[$fld] = $matches[1][count($matches[1]) - 1];
                    }

                    break;

                case 'ExtractEUSId':
                    // Look for EUS ID in text of the form "LastName, FirstName (12345)"
                    // This Regex matches any series of numbers between two parentheses

                    $patEUSId = '/\(( *\d+ *)\)/i';
                    $matches = array();

                    preg_match($patEUSId, $a[$fld], $matches);

                    // $matches is now a 1D array
                    // $matches[0] is the full match
                    // $matches[1] is the captured group

                    if (count($matches) > 1) {
                        // Update $a[$fld] to be the first match found (excluding the parentheses)

                        $a[$fld] = $matches[1];
                    }

                    break;

                case 'Scrub':
                    // Only copy certain text from the comment field of the source analysis job

                    // Look for text in the form '[Req:Username]'
                    // If found, put the last one found in $s
                    // For example, given '[Job:D3M580] [Req:D3M578] [Req:D3L243]', set $s to '[Req:D3L243]'
                    // This is a legacy comment text that was last used in 2010

                    $s = "";
                    $field = $a[$fld];

                    $patReq = '/(\[Req:[^\]]*\])/';
                    $matches = array();
                    preg_match_all($patReq, $field, $matches);

                    // The $matches array returned by preg_match_all is a 2D array
                    // Any matches to the first capture are in the array at $matches[0]

                    if (count($matches[0]) != 0) {
                        $s .= $matches[0][count($matches[0]) - 1];
                    }

                    // Also look for 'DTA:' followed by letters, numbers, and certain symbols
                    // For example, given '[Job:D3L243] [Req:D3L243] DTA:DTA_Manual_02', look for 'DTA:DTA_Manual_02'
                    // If found, append the text to $s
                    // This is a legacy comment text that was last used in 2010

                    $patDTA = '/(DTA:[a-zA-Z0-9_#\-]*)/';
                    preg_match($patDTA, $field, $matches);

                    // $matches is now a 1D array
                    // $matches[0] is the full match
                    // $matches[1] is the captured group

                    if (count($matches) > 1) {
                        $s .= " " . $matches[1];
                    }

                    $a[$fld] = $s;
                    break;
            }
        }
    }
    return $a;
}

/**
 * Override default values with values directly from URL segments
 * (based on matching segment and field order)
 * @param array $form_field_names
 * @param array $segs
 * @return array
 */
function get_values_from_segs(array $form_field_names, array $segs): array {
    // Include app/Helpers/wildcard_conversion_helper.php
    // helper('wildcard_conversion');
    // NOTE: As of Dec. 19, 2022, all calling methods are already doing URL/special value decoding
    //    Performing that again here ends up incorrectly converting '%xx' (from '__Wildcard__xx') into HTML entities

    $a = array();
    $seg_val = current($segs);
    foreach ($form_field_names as $field) {
        if ($seg_val === false) {
            break;
        }

        if ($seg_val != '-') {
            //$a[$field] = decode_special_values($seg_val); // see NOTE above
            $a[$field] = $seg_val;
        }
        $seg_val = next($segs);
    }
    return $a;
}

/**
 * Create the entry outcome message
 * @param string $message
 * @param string $option
 * @param string $id div attribute information
 * @return string
 */
function entry_outcome_message(string $message, string $option = 'success', string $id = ''): string {
    $str = '';
    $idWithTag = ($id) ? " id='$id'" : '';
    switch ($option) {
        case 'success':
            $str = "<div class='EPag_message' $idWithTag>" . $message . "</div>";
            break;
        case 'failure':
            $str = "<div class='EPag_error' $idWithTag>" . $message . "</div>";
            break;
        case 'error':
            $str = "<div class='bad_clr' $idWithTag>" . $message . "</div>";
            break;
        default:
            $str = "<div ${id}>" . $message . "</div>";
            break;
    }
    return $str;
}

/**
 * Make post-submission links to list report and detail report
 * "Go to list report" link is made by default if report action exists (unless overridden by "link_tag")
 *
 * Add rows to the general_params table in the Config DB to get additional post-submission links to appear
 * - detail_id: Makes "Go to detail report" link (if show action exists)
 *              using the specified entry page field as the identifier
 *              unless suppressed by link_tag.
 *              'post_submission_detail_id' in the General Params table
 * - link_tag:  Makes default "Go to list report" link point to a list report
 *              in a different page family, and prevents the
 *              "Go to detail report" link from appearing.
 *              'post_submission_link_tag' in the General Params table
 * - link:      Adds an arbitrary link shown following successfully submitting the entry
 *              'post_submission_link' in the General Params table
 *
 * @param string $tag
 * @param array $ps_link_specs
 * @param \stdClass $input_params
 * @param array $actions
 * @return string
 */
function make_post_submission_links(string $tag, array $ps_link_specs, \stdClass $input_params, array $actions): string {
    $lr_tg = '';
    $dr_tag = '';
    $id = '';

    // Get base url tag for post submission list report link
    if ($ps_link_specs['link_tag'] != '') {
        $lr_tg = $ps_link_specs['link_tag'];
    } else
    if ($actions['report']) {
        $lr_tg = $tag;
    } else {
        $lr_tg = '';
    }
    // Get base url tag for post submission detail report link
    if ($actions['show'] && $ps_link_specs['link_tag'] == '') {
        $dr_tag = $tag;
    } else {
        $id = '';
        $dr_tag = '';
    }
    // Get id for post submission link
    if ($ps_link_specs['detail_id'] != '') {
        $argName = $ps_link_specs['detail_id'];
        $id = $input_params->$argName;
    }
    $x_tag = ($ps_link_specs['link'] != '') ? json_decode($ps_link_specs['link'], true) : null;

    // Make the HTML
    $links = "";
    if ($lr_tg != '') {
        $url = site_url($lr_tg . "/report");
        $links .= "&nbsp; <a href='$url'>Go to list report</a>";
    }
    if ($dr_tag != '' && $id != '') {
        $url = site_url($dr_tag . "/show/" . $id);
        $links .= "&nbsp; <a href='$url'>Go to detail report</a>";
    }
    if ($x_tag != null) {
        $url = site_url($x_tag["link"] . $id);
        $links .= "&nbsp; <a href='$url'>" . $x_tag["label"] . "</a>";
    }
    return $links;
}
