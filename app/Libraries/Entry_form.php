<?php
namespace App\Libraries;

/**
 * Manages construction of an entry form (in different formats)
 */
class Entry_form {

    private $form_field_specs = array();
    private $field_values = array();
    private $field_enable = array();
    private $field_errors = array();

    /**
     * Flag for showing the help links on the entry form
     * @var boolean
     */
    private $include_help_link = true;
    private $file_tag = '';

    // --------------------------------------------------------------------
    function __construct() {

    }

    // --------------------------------------------------------------------
    function init($form_field_specs, $file_tag, $controller) {
        $this->form_field_specs = $form_field_specs;
        $this->file_tag = $file_tag;

        $this->controller = $controller;
        $this->set_field_values_to_default();
    }

    /**
     * Set current field values to defaults as defined by specs
     */
    private function set_field_values_to_default() {
        helper('user');

        foreach ($this->form_field_specs as $fldName => $spc) {
            $this->field_values[$fldName] = $this->get_default_value($this->controller, $fldName, $spc);
            $this->field_errors[$fldName] = '';
        }
    }

    /**
     * Get default value for field from spec
     * @param type $controller
     * @param string $fldName
     * @param type $f_spec
     * @return type
     */
    private function get_default_value($controller, $fldName, $f_spec) {
        $val = '';

        if (!array_key_exists('default', $f_spec) &&
                !array_key_exists('default_function', $f_spec) &&
                array_key_exists('section', $f_spec)) {
            $controller->message_box('Configuration Error', "In the config DB, form_field_options has a section entry "
                    . "named $fldName, but that is not a valid form field; "
                    . "update it to refer to a valid form field or remove the section");
            return $val;
        }

        if (isset($f_spec["default_function"])) {
            // if so, use specified function to get value
            $func_parts = explode(':', $f_spec["default_function"]);
            switch (strtolower($func_parts[0])) {
                case 'getuser()':
                    $val = get_user();
                    break;
                case 'currentdate':
                    $val = date("m/d/Y");
                    break;
                case 'previousweek':
                    $val = date("m/d/Y", strtotime('-1 week'));
                    break;
                case 'previousndays':
                    $interval = $func_parts[1] * -1;
                    $val = date("m/d/Y", strtotime("$interval day"));
                    break;
                case 'previousnweeks':
                    $interval = $func_parts[1] * -7;
                    $val = date("m/d/Y", strtotime("$interval day"));
                    break;
            }
        } else {
            // otherwise, use the literal default
            $val = $f_spec["default"];
        }
        return $val;
    }

    // --------------------------------------------------------------------
    function set_field_value($field, $value) {
        $this->field_values[$field] = $value;
    }

    // --------------------------------------------------------------------
    function set_field_error($field, $error) {
        $this->field_errors[$field] = $error;
    }

    // --------------------------------------------------------------------
    function set_field_enable($fields) {
        $this->field_enable = $fields;
    }

    /**
     * Build components for entry form into one of two arrays of rows,
     * one array for visible components of the form and the other for
     * invisible components, where each array row contains components
     * for an entry form row, and then call function to build HTML for
     * visible entry form
     * @param string $mode Typically 'add' or 'update' but could be 'add_trigger' or 'retry'
     * @return type
     */
    function build_display($mode) {
        $this->controller->choosers = model('App\Models\Dms_chooser');
        helper(['url', 'text', 'form']);

        $visible_fields = array();
        $hidden_fields = array();
        $block_number = 0;
        foreach ($this->form_field_specs as $fldName => $spec) {
            if (!array_key_exists('type', $spec)) {
                $this->controller->message_box('Configuration Error', "In the config DB, one of the tables refers to $fldName "
                        . "but that field is not defined in form_fields; see also "
                        . "the columns returned by the view or table specified by "
                        . "entry_page_data_table in general_params");
                continue;
            }

            // The form field type may contain several keywords specified by a vertical bar
            $fieldTypes = explode('|', $spec['type']);

            if (in_array('hidden', $fieldTypes)) {
                $val = $this->field_values[$fldName];
                $hidden_fields[] = "<input type='hidden' id='$fldName' name='$fldName' value='$val'/>";
            } else {
                // if field has section header attribute, add section header row to table
                if (array_key_exists('section', $spec)) {

                    if (!array_key_exists('label', $spec)) {
                        // Section name that points to a column that is not in the source data table or view
                        // A warning box should have already been displayed via get_default_value
                        continue;
                    }

                    $block_number++;
                    $visible_fields[] = array(-1, $spec['section'], $block_number);
                    // (someday) allow for enable field and section headers to be used together
                }
                $help = $this->make_wiki_help_link($spec['label']);
                $label = $spec['label'];
                $nonEditField = false;
                $field = $this->make_entry_field($fldName, $spec, $this->field_values[$fldName], $mode, $nonEditField);

                $showChooser = !$nonEditField;

                if ($showChooser && in_array('text-if-new', $fieldTypes)) {
                    if (substr($mode, 0, 3) === 'add' || $mode === 'retry') {
                        // Mode is likely add, though for dataset creation it will be add_trigger
                        // Mode will be retry if an exception occurred while calling a stored procedure and entry_cmd_mode was undefined
                        $showChooser = true;
                    } else {
                        // Mode is likely update
                        $showChooser = false;
                    }
                }

                if ($showChooser) {
                    $choosers = $this->controller->choosers->make_choosers($fldName, $spec);
                } else {
                    $choosers = "";
                }

                $error = $this->field_errors[$fldName]; //$this->make_error_field($this->field_errors[$fldName]);
                //
                $entry = $this->make_entry_area($field, $choosers, $error);
                $param = ($help) ? $help . '&nbsp;' . $label : $label;
                //
                if (!empty($this->field_enable)) {
                    $enable_ctrl = $this->make_field_enable_checkbox($fldName);
                    $visible_fields[] = array($block_number, $param, $enable_ctrl, $entry);
                } else {
                    $visible_fields[] = array($block_number, $param, $entry);
                }
            }
        }

        // magic command/mode field
        $attr = array('name' => 'entry_cmd_mode', 'id' => 'entry_cmd_mode', 'type' => 'hidden');
        $hidden_fields[] = form_input($attr);

        // package form display elements into final container
        $str = '';
        if (!empty($visible_fields)) {
            $str .= $this->display_table($visible_fields, empty($this->field_enable), $block_number);
        }
        $str .= implode("\n", $hidden_fields);
        return $str;
    }

    // ---------------------------------------------------------------------------------------------------------
    // HTML formatting function - maybe move to helper someday
    // ---------------------------------------------------------------------------------------------------------

    /**
     * Put content of visible fields into HTML table
     * @param type $visible_fields
     * @param type $has_enable_col
     * @param type $sections
     * @return string
     */
    private function display_table($visible_fields, $has_enable_col, $sections) {
        $str = "";
        $str .= "<table class='EPag'>\n";

        $header = ($has_enable_col) ? array('Parameter', 'Value') : array('Parameter', 'Enable', 'Value');
        $str .= "<tr>";
        foreach ($header as $head) {
            $str .= "<th>" . $head . "</th>";
        }
        $str .= "</tr>\n";

        /* FUTURE: enable this via general_params setting
          if($sections > 0) {
          $str .= "<tr>";
          $str .= "<td colspan=2>" . $this->make_master_section_controls() . "</td>";
          $str .= "</tr>\n";
          }
         */
        // place all visible fields into table cells in table rows
        foreach ($visible_fields as $row) {
            // remove the section number from the row fields (we don't display it)
            $section_number = array_shift($row);
            // if row is a section header, apply header formatting to field and table row
            $col_span = '';
            if ($section_number == -1) {
                $blk = array_pop($row); // retrieve and remove block number for section head
                $col_span = "colspan='2' class='section_block_header_all' id='section_block_header_$blk' ";
                $row[0] = $this->make_section_header($blk, $row[0]);
            }
            // define classes for section rows with section numbers greater than 0
            $class = '';
            if ($section_number > 0) {
                $class = "class='section_block_$section_number section_block_all'";
            }
            // place row fields in table cells in table row
            $str .= "<tr $class>";
            foreach ($row as $field) {
                $str .= "<td $col_span>" . $field . "</td>";
            }
            $str .= "</tr>\n";
        }
        $str .= "</table>\n";
        return $str;
    }

    // -----------------------------------
    private function make_master_section_controls() {
        $s = '';
        $himg = "<img src='" . base_url('/images/z_show_col.gif') . "' border='0' >";
        $simg = "<img src='" . base_url('/images/z_hide_col.gif') . "' border='0' >";
        $s .= "<a href='javascript:void(0)' onclick='epsilon.showHideSections(\"hide\", \"all\")'>$simg</a> Collapse All Sections ";
        $s .= '&nbsp;';
        $s .= "<a href='javascript:void(0)' onclick='epsilon.showHideSections(\"show\", \"all\")'>$himg</a> Expand All Sections ";
        return $s;
    }

    // -----------------------------------
    private function make_section_header($section_count, $section_label) {
        $s = "";
        $block_label = "section_block_$section_count";
        $marker = "<img id='" . $block_label . "_cntl" . "' src='" . base_url('/images/z_hide_col.gif') . "' border='0' >";
        $s .= "<a href='javascript:void(0)' onclick='epsilon.showHideTableRows(\"$block_label\", \"" . base_url() . "/images/\", \"z_show_col.gif\", \"z_hide_col.gif\")'>$marker</a>";
        $s .= "&nbsp; <strong>" . $section_label . "</strong>";
        return $s;
    }

    // -----------------------------------
    private function make_field_enable_checkbox($fldName) {
        $str = '';
        if (array_key_exists($fldName, $this->field_enable)) {
            $ckbx_id = $fldName . '_ckbx_enable';
            $click = "onClick='epsilon.enableDisableField(this, \"$fldName\")'";
            switch (strtolower($this->field_enable[$fldName])) {
                case 'enabled':
                    $str = "<input type='checkbox' class='_ckbx_enable' name='$ckbx_id' $click checked='yes' >";
                    break;
                case 'disabled':
                    $str = "<input type='checkbox' class='_ckbx_enable' name='$ckbx_id' $click >";
                    break;
                case 'none':
                    $str = '';
                    break;
            }
        }
        return $str;
    }

    /**
     * Package components of entry area
     * @param type $field
     * @param type $choosers
     * @param type $error
     * @return string
     */
    private function make_entry_area($field, $choosers, $error) {
        $str = '';
        $str .= "<table>";
        $str .= "<tr>";
        $str .= "<td>" . $field . "</td>";
        $str .= "<td style='vertical-align:bottom'>" . $choosers . "</td>";
        $str .= "</tr>";
        if ($error) {
            $str .= "<tr><td colspan='2'>" . $error . "</td></tr>";
        }
        $str .= "</table>";
        return $str;
    }

    /**
     * Make an entry form field
     *
     * @param type $field_name Field name
     * @param type $f_spec Field spec
     * @param type $cur_value Current value
     * @param string $mode Typically 'add' or 'update' but could be 'add_trigger' or 'retry'
     * @param bool $nonEditField Will be set to true if the field is non-edit
     * @return type
     */
    private function make_entry_field($field_name, $f_spec, $cur_value, $mode, &$nonEditField) {
        $s = "";
        $nonEditField = false;

        // set up delimiter for lists for the field
        $delimFromSpec = (isset($f_spec['chooser']['Delimiter'])) ? $f_spec['chooser']['Delimiter'] : '';
        $delim = ($delimFromSpec != '') ? $delimFromSpec : ',';

        $data['name'] = $field_name;
        $data['id'] = $field_name;
        $data['value'] = $cur_value;

        // The form field type may contain several keywords specified by a vertical bar
        $fieldTypes = explode('|', $f_spec['type']);

        if (in_array('text-if-new', $fieldTypes)) {
            // Replace text-if-new with either 'text' or 'non-edit'
            // First remove 'text-if-new'
            $fieldTypes = array_merge(array_diff($fieldTypes, array('text-if-new')));

            if (substr($mode, 0, 3) === 'add' || $mode === 'retry') {
                // Mode is likely add, though for dataset creation it will be add_trigger
                // Mode will be retry if an exception occurred while calling a stored procedure and entry_cmd_mode was undefined
                $fieldTypes[] = 'text';
            } else {
                $fieldTypes[] = 'non-edit';
            }
        } else if (in_array('text-nocopy', $fieldTypes) || in_array('area-nocopy', $fieldTypes)) {
            if (substr($mode, 0, 3) === 'add') {
                // Blank out the value to force the user to re-define it for this new entry
                // (or, for non-edit fields, blank out the fields because it is not relevant to the new item)
                $data['value'] = '';
            }

            if (!in_array('non-edit', $fieldTypes)) {
                // Replace 'text-nocopy' or 'area-nocopy' with 'text' or 'area'

                if (in_array('area-nocopy', $fieldTypes)) {
                    $fieldTypes = array_merge(array_diff($fieldTypes, array('area-nocopy')));
                    $fieldTypes[] = 'area';
                } else {
                    $fieldTypes = array_merge(array_diff($fieldTypes, array('text-nocopy')));
                    $fieldTypes[] = 'text';
                }
            }
        } else if (in_array('non-edit-if-data-package', $fieldTypes)) {
            // Possibly make this field 'non-edit'
            // First remove the field type flag
            $fieldTypes = array_merge(array_diff($fieldTypes, array('non-edit-if-data-package')));

            // Make non-edit if a data package is defined
             foreach ($this->form_field_specs as $comparisonFldName => $comparisonFldSpec) {
                if ($comparisonFldName != "Data_Package_ID"){
                    continue;
                }

                $dataPackageID = intval($this->field_values[$comparisonFldName]);

                if ($dataPackageID > 0){
                    // Replace 'area' with 'non-edit'
                    $fieldTypes = array_merge(array_diff($fieldTypes, array('area')));
                    $fieldTypes[] = 'non-edit';
                }
                break;
            }
        }

        // create HTML according to field type
        if (in_array('text', $fieldTypes)) {
            $data['maxlength'] = $f_spec['maxlength'];
            $data['size'] = $f_spec['size'];
            $data = $this->add_chooser_properties($field_name, $f_spec, $data);
            $s .= form_input($data);
        } else if (in_array('area', $fieldTypes)) {
            $data['rows'] = $f_spec['rows'];
            $data['cols'] = $f_spec['cols'];
            $autoFormatDelimitedList = true;
            if (isset($f_spec['auto_format'])) {
                // auto_format is defined in the form_field_options table in the config DB
                switch (strtolower($f_spec['auto_format'])) {
                    case 'xml':
                        $data['onBlur'] = "epsilon.formatXMLText('" . $data['id'] . "')";
                        $autoFormatDelimitedList = false;
                        break;
                    case 'none':
                    case 'mono':
                    case 'monospace':
                        // Do not alter the text at all
                        $autoFormatDelimitedList = false;
                        break;
                    default:
                        // Unrecognized auto_format spec
                        // Leave $autoFormatDelimitedList as true
                        break;
                }
            }
            if ($autoFormatDelimitedList) {
                // Replace carriage returns and linefeeds with the delimiter
                $data['onChange'] = "epsilon.convertList('" . $data['id'] . "', '" . $delim . "')";
            }
            $data = $this->add_chooser_properties($field_name, $f_spec, $data);
            $s .= form_textarea($data);
        } else if (in_array('non-edit', $fieldTypes)) {
            $s .= '<input type="hidden" name="' . $data['name'] . '" value="' . $data['value'] . '" id="' . $data['id'] . '" />';
            $s .= $data['value'];
            $nonEditField = true;
        } else if (in_array('hidden', $fieldTypes)) {

            $s .= "<input type='hidden' id='$field_name' name='$field_name' value='xx'/>";
//          $s .= form_hidden($data['name'], $data['value']);
        } else if (in_array('file', $fieldTypes)) {
            // This form field type is unused as of June 2017
            $data['maxlength'] = $f_spec['maxlength'];
            $data['size'] = $f_spec['size'];
            $s .= form_upload($data);
        } else if (in_array('checkbox', $fieldTypes)) {
            $lbl = $f_spec['label'];
            $checked = ($data['value']) ? "checked=true" : "";
            $s .= "<input type='checkbox' name='$field_name' id='$field_name' value='Yes' $checked />$lbl Enabled<br/>";
        } else if (in_array('action', $fieldTypes)) {
            $px = explode(':', $f_spec['default']);
            $fnx = $px[0];
            $lbx = $px[1];
            $dsx = $px[2];
            $s .= "<a href='javascript:void(0)' onclick='$fnx' >$lbx</a> $dsx";
//          $s .= "<button name='${f_name}_btn' onclick='$fnx' class='button'>$lbx</button>";
        }

        return $s;
    }

    /**
     * Get attributes to be added to input field for auto complete
     * @param type $field_name Field name
     * @param type $f_spec field spec
     * @param type $props
     * @return string
     */
    function add_chooser_properties($field_name, $f_spec, $props) {
        if (array_key_exists("chooser_list", $f_spec)) {
            $chsr = $f_spec['chooser_list'][0];
            if ($chsr["type"] == 'autocomplete' || $chsr["type"] == 'autocomplete.append') {
                $props['class'] = 'dms_autocomplete_chsr';
                $props['data-query'] = $chsr["PickListName"];
            }
            if ($chsr["type"] == 'autocomplete.append') {
                $props['data-append'] = 'true';
            }
        }
        return $props;
    }

    /**
     * Create the link to the wiki page (provided $this->include_help_link is true)
     * Example: http://prismwiki.pnl.gov/wiki/DMS_Help_for_analysis_job_request#ID
     * @param type $label
     * @return string
     */
    private function make_wiki_help_link($label) {
        $s = "";
        if ($this->include_help_link) {
            $file_tag = $this->file_tag;
            $nsLabel = str_replace(" ", "_", $label);
            $pwiki = config('App')->pwiki;
            $wiki_helpLink_prefix = config('App')->wikiHelpLinkPrefix;
            $href = "${pwiki}${wiki_helpLink_prefix}${file_tag}#${nsLabel}";
            $s .= "<a class=help_link target = '_blank' title='Click to explain field " . $label . "' href='" . $href . "'><img src='" . base_url('/images/help.png') . "' border='0' ></a>";
        }
        return $s;
    }

    // -----------------------------------
    function get_mode_from_page_type($page_type) {
        return ($page_type == 'edit') ? 'update' : 'add';
    }

    // -----------------------------------
    function make_entry_commands($entry_commands, $page_type) {
        $str = '';

        $mode = $this->get_mode_from_page_type($page_type);

        // Default command button
        $attributes['id'] = 'primary_cmd';
        $url = site_url($this->file_tag . "/submit_entry_form");
        $attributes['onclick'] = "epsilon.submitStandardEntryPage('$url', '$mode')";
        $attributes['content'] = ($page_type == 'create') ? 'Create' : 'Update';
        $attributes['class'] = 'button entry_cmd_button';

        // Is there an override for the default command button?
        foreach ($entry_commands as $command => $spec) {
            if ($spec['type'] == 'override' and $spec['target'] == $mode) {
                $attributes['onclick'] = "epsilon.submitStandardEntryPage('$url', '$command')";
                $attributes['content'] = $spec['label'];
                $attributes['title'] = $spec['tooltip'];
                break;
            }
        }
        $str .= form_button($attributes) . "<br>\n";

        // Supplemental commands
        foreach ($entry_commands as $command => $spec) {
            switch (strtolower($spec['type'])) {
                case 'cmd':
                	if ($command == 'PreviewAdd' And $page_type != 'create') {
                		// Only show the PreviewAdd button when creating a new item
                		break;
                	}

                    $attributes = array();
                    $attributes['id'] = 'cmd_' . strtolower(str_replace(' ', '_', $command));
                    $attributes['content'] = $spec['label'];
                    $attributes['onclick'] = "epsilon.submitStandardEntryPage('$url', '$command')";
                    $attributes['title'] = $spec['tooltip'];
                    $attributes['class'] = 'button entry_cmd_button';
                    $str .= form_button($attributes) . "<br>\n";
                    break;
                case 'retarget':
                    $target_url = site_url($spec['target']);
                    $attributes = array();
                    $attributes['content'] = $spec['label'];
                    $attributes['onclick'] = "epsilon.submitEntryFormToOtherPage('$target_url', '$command')";
                    $attributes['title'] = $spec['tooltip'];
                    $attributes['class'] = 'button entry_cmd_button';
                    $str .= form_button($attributes) . "<br>\n";
                    break;
            }
        }
        return $str;
    }

    // ---------------------------------------------------------------------------------------------------------
    // form field adjustment section
    // ---------------------------------------------------------------------------------------------------------

    /**
     * Modify field specs to account for field edit permissions
     * @param type $userPermissions
     */
    function adjust_field_permissions($userPermissions) {
        // look at each field
        foreach ($this->form_field_specs as $field_name => $f_spec) {
            // find ones that have permission restrictions
            if (array_key_exists('permission', $f_spec)) {
                // do user's permisssions satisfy field restrictions?
                $fieldPermissions = explode(',', $f_spec['permission']);
                $hits = array_intersect($fieldPermissions, $userPermissions);
                // no - change spec to make field non-editable
                // and remove chooser (if one exists)
                if (count($hits) == 0) {
                    $this->form_field_specs[$field_name]['type'] = 'non-edit';
                    if (array_key_exists('chooser_list', $f_spec)) {
                        unset($this->form_field_specs[$field_name]['chooser_list']);
                    }
                }
            }
        }
    }

    /**
     * Change the visibility of designated fields according to given entry mode
     * @param type $mode Page mode: 'add' or 'update'
     */
    function adjust_field_visibility($mode) {
        foreach ($this->form_field_specs as $field_name => $f_spec) {
            // hide is defined in the form_field_options table in the config DB
            if (array_key_exists('hide', $f_spec)) {
                if ($mode === $f_spec["hide"]) {
                    $this->form_field_specs[$field_name]["type"] = "hidden";
                }
            }
        }
    }
}
?>
