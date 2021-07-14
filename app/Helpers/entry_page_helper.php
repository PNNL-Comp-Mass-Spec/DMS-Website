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
 * @param type $segs
 * @param type $config_source
 * @param type $form_field_names
 * @return type
 */
function get_initial_values_for_entry_fields($segs, $config_source, $form_field_names) {
    $CI =& get_instance();

    $initial_field_values = array();

    $num_segs = count($segs);
    //
    if ($num_segs == 0) {
        // just accept defaults
    } else
    if ($num_segs == 1) {
        // get values from database using source and id that we were given
        $id = $segs[0];
        $CI->load_mod('q_model', 'input_model', 'entry_page', $config_source);
        $initial_field_values = $CI->input_model->get_item($id);
    } else
    if ($num_segs > 1) {
        // get values from an external source
        $source = $segs[0];
        $id = $segs[1];
        if ($source == 'init') {
            $segs = array_slice($segs, 1);
            // get values from url segments:
            $initial_field_values = get_values_from_segs($form_field_names, $segs);
        } else
        if ($source == 'post') {
            // (someday) get values from POST
        } else {
            // get external source mapping
            $col_mapping = $CI->form_model->get_external_source_field_map($source);
            if ($col_mapping) {
                // get values from database using source and id plucked from url
                $CI->load_mod('q_model', 'input_model', 'detail_report', $source);
                $source_data = $CI->input_model->get_item($id);
                $initial_field_values = load_from_external_source($col_mapping, $source_data);
            }
        }
    }
    return $initial_field_values;
}

/**
 * Return an array (of field => value) containing fields defined
 * in $col_mapping with values according to type of mapping defined
 * @param type $col_mapping
 * @param type $source_data
 * @return string
 */
function load_from_external_source($col_mapping, $source_data) {
    $CI =& get_instance();

    $a = array();
    // load entry fields from external source
    foreach ($col_mapping as $fld => $spec) {
        switch ($spec['type']) {
            case 'ColName':
                $a[$fld] = $source_data[$spec['value']];
                break;
            case 'PostName':
                $pv = $CI->input->post($spec['value']);
                $a[$fld] = $pv;
                break;
            case 'Literal':
                $a[$fld] = $spec['value'];
                break;
        }
        // any further actions?
        if (isset($spec['action'])) {
            switch ($spec['action']) {
                case 'Scrub':
                    $s = "";
                    $field = $a[$fld];

                    $patReq = '/(\[Req:[^\]]*\])/';
                    $matches = array();
                    preg_match_all($patReq, $field, $matches);
                    if (count($matches[0]) != 0) {
                        $s .= $matches[0][count($matches[0]) - 1];
                    }

                    $patDTA = '/(DTA:[a-zA-Z0-9_#\-]*)/';
                    preg_match($patDTA, $field, $matches);
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
 * @param type $form_field_names
 * @param type $segs
 * @return type
 */
function get_values_from_segs($form_field_names, $segs) {
    // Include app/Helpers/wildcard_conversion_helper.php
    helper('wildcard_conversion');

    $a = array();
    $seg_val = current($segs);
    foreach ($form_field_names as $field) {
        if ($seg_val === false) {
            break;
        }

        if ($seg_val != '-') {
            $a[$field] = convert_special_values($seg_val);
        }
        $seg_val = next($segs);
    }
    return $a;
}

/**
 * Create the entry outcome message
 * @param type $message
 * @param type $option
 * @param type $id
 * @return string
 */
function entry_outcome_message($message, $option = 'success', $id = '') {
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
            $str = "<div${id}>" . $message . "</div>";
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
 * @param type $tag
 * @param type $ps_link_specs
 * @param type $input_params
 * @param type $actions
 * @return string
 */
function make_post_submission_links($tag, $ps_link_specs, $input_params, $actions) {
    $lr_tg = '';
    $dr_tag = '';
    $id = '';

    // get base url tag for post submission list report link
    if ($ps_link_specs['link_tag'] != '') {
        $lr_tg = $ps_link_specs['link_tag'];
    } else
    if ($actions['report']) {
        $lr_tg = $tag;
    } else {
        $lr_tg = '';
    }
    // get base url tag for post submission detail report link
    if ($actions['show'] && $ps_link_specs['link_tag'] == '') {
        $dr_tag = $tag;
    } else {
        $id = '';
        $dr_tag = '';
    }
    // get id for post submission link
    if ($ps_link_specs['detail_id'] != '') {
        $argName = $ps_link_specs['detail_id'];
        $id = $input_params->$argName;
    }
    $x_tag = ($ps_link_specs['link'] != '') ? json_decode($ps_link_specs['link'], true) : null;

    // make the HTML
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
