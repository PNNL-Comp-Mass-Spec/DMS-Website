<?php
namespace App\Libraries;

// --------------------------------------------------------------------
// Entry page section
// --------------------------------------------------------------------

class Entry {

    protected $config_source = '';
    protected $tag = '';
    protected $title = '';

    /**
     * Constructor
     */
    function __construct() {

    }

    /**
     * Initialize this class
     * @param string $config_name Not used
     * @param string $config_source Configuration source
     * @param type $controller
     */
    function init($config_name, $config_source, $controller) {
        $this->config_source = $config_source;

        $this->controller = $controller;
        $this->tag = $this->controller->my_tag;
        $this->title = $this->controller->my_title;
    }

    /**
     * Make an entry page to create or update a record in the database (according to $page_type)
     * The entry form is subsequently submitted via AJAX call to function submit_entry_form.
     * @param string $page_type
     */
    function create_entry_page($page_type) {
        helper(['entry_page', 'url']);

        // General specifications for page family
        $this->controller->load_mod('G_model', 'gen_model', 'na', $this->config_source);

        // Make entry form object using form definitions from model
        $this->controller->load_mod('E_model', 'form_model', 'na', $this->config_source);
        $form_def = $this->controller->form_model->get_form_def(array('fields', 'specs', 'entry_commands', 'enable_spec'));
        $form_def->field_enable = $this->get_field_enable($form_def->enable_spec);
        //
        $this->controller->load_lib('Entry_form', $form_def->specs, $this->config_source);

        // Determine the page mode ('add' or 'update')
        $mode = $this->controller->entry_form->get_mode_from_page_type($page_type);

        // Get initial field values and merge them with form object
        $segs = array_slice(getCurrentUriDecodedSegments(), 2); // remove controller and function segments
        $initial_field_values = get_initial_values_for_entry_fields($segs, $this->config_source, $form_def->fields, $this->controller);

        if (empty($initial_field_values)) {
            if ($page_type == 'edit') {
                if (!empty($segs) && sizeof($segs) > 0) {
                    $this->controller->message_box('Edit Error', "Entity '$segs[0]' not found");
                } else {
                    $this->controller->message_box('Edit Error', "Entity ID not specified for editing");
                }
                return;
            }
        } else {
            foreach ($initial_field_values as $field => $value) {

                // Entry views in DMS can append __NoCopy__ to a field when we do not want the field value to be copied to new entries
                // For example, see V_Sample_Prep_Request_Entry
                if (EndsWith($value, '__NoCopy__')) {
                    if (substr($mode, 0, 3) === 'add') {
                        // Creating a new item (either from scratch or by copying an existing item)
                        // Blank out the field
                        $value = '';
                    } else {
                        // Editing an item; remove the NoCopy flag
                        $value = substr($value, 0, strlen($value) - strlen('__NoCopy__'));
                    }
                }

                $this->controller->entry_form->set_field_value($field, $value);
            }
        }

        // Handle special field options for entry form object
        $this->handle_special_field_options($form_def, $mode);

        // Build page display components and load page
        $data['tag'] = $this->tag;
        $data['my_tag'] = $this->controller->my_tag;
        $data['title'] = $this->controller->gen_model->get_page_label($this->title, $page_type);
        $data['form'] = $this->controller->entry_form->build_display($mode);
        $data['entry_cmds'] = $this->handle_cmd_btns($this->controller, $form_def->entry_commands, $page_type);
        $data['entry_submission_cmds'] = $this->controller->gen_model->get_param('entry_submission_cmds');
        $data['page_type'] = $page_type;
        $data['url_segments'] = implode('/', $segs);

        helper(['menu', 'link_util']);
        $data['nav_bar_menu_items'] = set_up_nav_bar('Entry_Pages', $this->controller);
        echo view('main/entry_form', $data);
    }

    /**
     * Handle command buttons
     * @param object $me
     * @param mixed $commands Array of strings
     * @param string $page_type
     */
    protected function handle_cmd_btns($me, $commands, $page_type) {
        $btns = '';
        $suppress_btns = $me->gen_model->get_param('cmd_buttons');
        if (!$suppress_btns) {
            $btns = $me->entry_form->make_entry_commands($commands, $page_type);
        }
        return $btns;
    }

    /**
     * Handle special field options for entry form object
     * @param stdClass $form_def
     * @param string $mode Page mode: 'add' or 'update'
     */
    protected function handle_special_field_options($form_def, $mode) {
        $this->controller->entry_form->set_field_enable($form_def->field_enable);

        if ($mode) {
            $this->controller->entry_form->adjust_field_visibility($mode);
        }

        $userPermissions = $this->controller->auth->get_user_permissions(get_user());
        $this->controller->entry_form->adjust_field_permissions($userPermissions);
    }

    /**
     *  Create or update entry in database from entry page form fields in POST:
     *  use entry form definition from config db
     *  validate entry form information, submit to database if valid,
     *  and return HTML containing entry form with updated values
     *  and success/failure messages
     * @category AJAX
     */
    function submit_entry_form() {
        helper(['entry_page']);

        $this->controller->load_mod('E_model', 'form_model', 'na', $this->config_source);
        $form_def = $this->controller->form_model->get_form_def(array('fields', 'specs', 'rules', 'enable_spec'));
        $form_def->field_enable = $this->get_field_enable($form_def->enable_spec);

        $request = \Config\Services::request();
        $postData = $request->getPost();
        $preformat = new \App\Libraries\ValidationPreformat();
        $postData = $preformat->run($postData, $form_def->rules);

        $validation = \Config\Services::validation();
        $validation->setRules($form_def->rules);
        $valid_fields = $validation->run($postData);

        // Get field values from validation object
        $input_params = new \stdClass();
        foreach ($form_def->fields as $field) {
            if (array_key_exists($field, $postData) === false) {
                // The form field is not in the POST data
                // For checkbox fields, if a checkbox is unchecked, it will not be in $postData
                // See, for example, https://dmsdev.pnl.gov/analysis_job_request_psm/create
                
                // The analysis_job_request_psm page also has a form field named 'ignore_me',
                // which is a placeholder for the "Get suggested values" link; this field is also not in $postData
                continue;
            }

            $input_params->$field = $postData[$field];
        }
        try {
            if (!$valid_fields) {
                throw new \Exception('There were validation errors');
            }

            // $msg is an output parameter of call_stored_procedure
            $msg = '';
            $this->call_stored_procedure($input_params, $form_def, $msg);

            // Everything worked - compose tidings of joy
            $ps_links = $this->get_post_submission_link($input_params);
            $message = 'Operation was successful';
            if (empty($msg)) {
                $outcome = $this->outcome_msg($message, 'normal');
            } else {
                // Define $outcome as "Operation was successful: message"
                $outcome = $this->outcome_msg($message . ": " . $msg, 'normal');
            }
            $supplement = $this->supplement_msg($message . $ps_links, 'normal');
        } catch (\Exception $e) {
            // Something broke - compose expressions of regret
            $message = $e->getMessage();     // . " (page family: $this->tag)";
            $outcome = $this->outcome_msg($message, 'failure');
            $supplement = $this->supplement_msg($message, 'error');

            // Read the value of $_POST['entry_cmd_mode']
            $entryCmdMode = filter_input(INPUT_POST, 'entry_cmd_mode', FILTER_SANITIZE_STRING);

            // Add or update the mode property of the input params
            if (empty($entryCmdMode)) {
                $input_params->mode = 'retry';
            } else {
                $input_params->mode = $entryCmdMode;
            }
        }

        // Get entry form object and use to to build and return HTML for form
        $this->controller->load_lib('Entry_form', $form_def->specs, $this->config_source);
        $data['form'] = $this->make_entry_form_HTML($input_params, $form_def, $validation);
        echo $outcome;
        echo $data['form'];
        echo $supplement;
    }

    // --------------------------------------------------------------------
    private function outcome_msg($message, $option) {
        return entry_outcome_message($message, $option, 'main_outcome_msg');
    }

    private function supplement_msg($message, $option) {
        return entry_outcome_message($message, $option, 'supplement_outcome_msg');
    }

    /**
     * Get entry form builder object and use it to make HTML
     * @param stdClass $input_params
     * @param stdClass $form_def
     * @param stdClass $validation
     */
    protected function make_entry_form_HTML($input_params, $form_def, $validation) {
        helper('form');

        // Handle special field options for entry form object
        $mode = (property_exists($input_params, 'mode')) ? $input_params->mode : '';
        $this->handle_special_field_options($form_def, $mode);

        // Update entry form object with field values
        // and any field validation errors
        foreach ($form_def->fields as $field) {
            if(property_exists($input_params, $field) === false)
            { 
                // The field is not defined as a property in the $input_params class
                continue;
            }

            $this->controller->entry_form->set_field_value($field, $input_params->$field);
            $fieldError = validation_error($validation, $field, '<span class="bad_clr">', '</span>');
            $this->controller->entry_form->set_field_error($field, $fieldError);
        }

        // Build HTML and return it
        return $this->controller->entry_form->build_display($mode);
    }

    /**
     * Make post-submission links to list report and detail report
     * @param stdClass $input_params
     */
    protected function get_post_submission_link($input_params) {
        $ps_link_specs = $this->controller->gen_model->get_post_submission_link_specs();
        $actions = $this->controller->gen_model->get_actions();
        return make_post_submission_links($this->controller->my_tag, $ps_link_specs, $input_params, $actions);
    }

    /**
     * Call a stored procedure
     * @param stdClass $input_params
     * @param stdClass $form_def
     * @param string $msg Message returned by the stored procedure (output)
     */
    protected function call_stored_procedure($input_params, $form_def, &$msg) {
        $ok = $this->controller->load_mod('S_model', 'sproc_model', 'entry_sproc', $this->config_source);
        if (!$ok) {
            throw new \Exception($this->controller->sproc_model->get_error_text());
        }

        $calling_params = $this->make_calling_param_object($input_params, $form_def->field_enable);
        $success = $this->controller->sproc_model->execute_sproc($calling_params);
        if (!$success) {
            throw new \Exception($this->controller->sproc_model->get_error_text());
        }

        $msg = $this->controller->sproc_model->get_parameters()->message;
        $this->update_input_params_from_stored_procedure_args($input_params);
    }

    /**
     * Copy values from params that were bound to stored procedure arguments
     * back to input param object
     * @param stdClass $input_params
     */
    protected function update_input_params_from_stored_procedure_args($input_params) {
        $bound_params = $this->controller->sproc_model->get_parameters();
        foreach ($this->controller->sproc_model->get_sproc_args() as $arg) {
            if ($arg['dir'] == 'output') {
                $fn = ($arg['field'] == '<local>') ? $arg['name'] : $arg['field'];
                if (isset($input_params->$fn) && $bound_params->$fn != '[no change]') {
                    $input_params->$fn = $bound_params->$fn;
                }
            }
        }
        if (isset($bound_params->mode)) {
            $input_params->mode = $bound_params->mode;
        }
    }

    /**
     * Copy (shallow) input param object to proxy object
     * for actually supplying values to call stored procedure
     * Returns a copy of $input_params, with disabled fields changed to [no change]
     * @param mixed $input_params
     * @param mixed $field_enable
     * @return mixed
     */
    protected function make_calling_param_object($input_params, $field_enable) {
        $calling_params = clone $input_params;
        $calling_params->mode = \Config\Services::request()->getPost('entry_cmd_mode');
        $calling_params->callingUser = get_user();

        // Adjust calling parameters for any disabled fields
        foreach ($field_enable as $field => $status) {
            if ($status == 'disabled') {
                $calling_params->$field = '[no change]';
            }
        }
        return $calling_params;
    }

    /**
     * Update input field list with enable/disable status from POST.
     * Input field list designates whether or not each field has an
     * enable/disable checkbox field and the ones that do have will be
     * updated from the checked state of the associated checkboxes from POST.
     * @param mixed $field_enable
     * @return mixed
     */
    protected function get_field_enable($field_enable) {
        $suffix = '_ckbx_enable';
        foreach ($field_enable as $f_name => $mode) {
            $enable_field_name = $f_name . $suffix;
            if ($mode != 'none') {
                if (array_key_exists($enable_field_name, $_POST)) {
                    $field_enable[$f_name] = 'enabled';
                } else {
                    $field_enable[$f_name] = 'disabled';
                }
            }
        }
        return $field_enable;
    }
}
?>
