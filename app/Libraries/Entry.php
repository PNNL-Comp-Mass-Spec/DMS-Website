<?php
namespace App\Libraries;

use \CodeIgniter\Validation\ValidationInterface;

// --------------------------------------------------------------------
// Entry page section
// --------------------------------------------------------------------

class Entry {

    private \App\Controllers\BaseController $controller;
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
     * @param \App\Controllers\BaseController $controller
     */
    function init(string $config_name, string $config_source, \App\Controllers\BaseController $controller) {
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

        // Get id/initial field values from segments
        $segments = array_slice(getCurrentUriDecodedSegments(), 2); // remove controller and function segments

        $entryData = $this->create_entry_data($page_type, $segments);

        if (!$entryData->success)
        {
            $this->controller->message_box('Edit Error', $entryData->editError);
            return;
        }

        // Build page display components and load page
        $data['tag'] = $this->tag;
        $data['my_tag'] = $this->controller->my_tag;
        $data['title'] = $this->controller->gen_model->get_page_label($this->title, $page_type);
        $data['form'] = $this->controller->entry_form->build_display($entryData->mode);
        $data['entry_cmds'] = $this->handle_cmd_btns($this->controller, $entryData->form_def->entry_commands, $page_type);
        $data['entry_submission_cmds'] = $this->controller->gen_model->get_param('entry_submission_cmds');
        $data['page_type'] = $page_type;
        $data['url_segments'] = implode('/', $segments);

        helper(['menu', 'link_util']);
        $data['nav_bar_menu_items'] = set_up_nav_bar('Entry_Pages', $this->controller);
        echo view('main/entry_form', $data);
    }

    /**
     * Make an entry page json object that can be populated for creating a new entry, or edited for updating an entry
     * The entry json object is later submitted via POST call to function api_create or (PUT/PATCH/POST) api_update.
     * @param string $page_type
     * @param array $segments URL segments, can be either just an ID, or 2 entries with an entity name and id
     */
    public function create_entry_json(string $page_type, array $segments) {
        \Config\Services::response()->setContentType("application/json");
        echo json_encode($this->create_entry_array_work($page_type, $segments));
    }

    /**
     * Make an entry page array that can be populated for creating a new entry, or edited for updating an entry
     * The entry data array is later submitted via POST call to function api_create or (PUT/PATCH/POST) api_update.
     * @param string $page_type
     * @param string $id
     * @return array
     */
    public function create_entry_array($page_type, $id = '') : array {
        $segments = array();
        if ($page_type == 'edit')
        {
            $segments[] = $id;
        }

        return $this->create_entry_array_work($page_type, $segments);
    }

    /**
     * Make an entry page array that can be populated for creating a new entry, or edited for updating an entry
     * The entry data array is later submitted via POST call to function api_create or (PUT/PATCH/POST) api_update.
     * @param string $page_type
     * @param array $segments
     * @return array
     */
    public function create_entry_array_work($page_type, array $segments) : array {
        $entryData = $this->create_entry_data($page_type, $segments);

        if (!$entryData->success)
        {
            return array("error" => $entryData->editError);
        }

        // Build page data into an array
        return $entryData->entry_form->build_entry_array($entryData->mode);
    }

    /**
     * Make an entry page object that can be populated for creating a new entry, or edited for updating an entry
     * The entry data is later submitted via POST call from website (AJAX) or REST (api_create POST, api_update PUT/PATCH/POST)
     * @param string $page_type
     * @param array $segments URL segments; can be empty if $page_type = 'create'; if $page_type = 'edit', must not be empty, and $segments[0] needs to be an entity ID value
     * @return \stdClass
     */
    private function create_entry_data($page_type, array $segments) : \stdClass {
        helper(['entry_page', 'url']);
        $entryData = new \stdClass();

        // General specifications for page family
        $this->controller->loadGeneralModel('na', $this->config_source);

        // Make entry form object using form definitions from model
        $this->controller->loadFormModel('na', $this->config_source);
        $form_def = $this->controller->form_model->get_form_def(array('fields', 'specs', 'entry_commands', 'enable_spec'));
        $form_def->field_enable = $this->get_field_enable($form_def->enable_spec);
        //
        $this->controller->loadEntryFormLibrary($form_def->specs, $this->config_source);

        // Determine the page mode ('add' or 'update')
        $mode = $this->controller->entry_form->get_mode_from_page_type($page_type);

        // Get initial field values and merge them with form object
        $initial_field_values = get_initial_values_for_entry_fields($segments, $this->config_source, $form_def->fields, $this->controller);

        if (empty($initial_field_values)) {
            if ($page_type == 'edit') {
                $entryData->success = false;
                $entryData->editError = '';
                if (count($segments) > 0) {
                    $entryData->editError = "Entity with ID '$segments[0]' not found";
                } else {
                    $entryData->editError = "Entity ID not specified for editing";
                }
                return $entryData;
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

        // Store the return data in an object
        $entryData->success = true;
        $entryData->entry_form = $this->controller->entry_form;
        $entryData->form_def = $form_def;
        $entryData->mode = $mode;

        return $entryData;
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
     * @param \stdClass $form_def
     * @param string $mode Page mode: 'add' or 'update'
     */
    protected function handle_special_field_options(\stdClass $form_def, $mode) {
        $this->controller->entry_form->set_field_enable($form_def->field_enable);

        if ($mode) {
            $this->controller->entry_form->adjust_field_visibility($mode);
        }

        $userPermissions = $this->controller->getAuth()->get_user_permissions(get_user());
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
    public function submit_entry_form() {
        $request = \Config\Services::request();
        $postData = $request->getPost();

        // Read the value of $_POST['entry_cmd_mode']
        $mode = \Config\Services::request()->getPost('entry_cmd_mode');

        $resultData = $this->submit_entry_data($postData, $mode);
        $input_params = $resultData->input_params;
        $form_def = $resultData->form_def;
        $validation = $resultData->validation;
        $message = $resultData->message;
        $outcomeText = $resultData->outcome;
        $outcome = '';
        $supplement = '';

        if ($resultData->success)
        {
            $ps_links = $this->get_post_submission_link($input_params);
            $outcome = $this->outcome_msg($outcomeText, 'normal');
            $supplement = $this->supplement_msg($message . $ps_links, 'normal');
        }
        else
        {
            $outcome = $this->outcome_msg($outcomeText, 'failure');
            $supplement = $this->supplement_msg($outcomeText, 'error');
        }

        // Get entry form object and use to to build and return HTML for form
        $this->controller->loadEntryFormLibrary($form_def->specs, $this->config_source);
        $this->process_submission_results($input_params, $form_def, $validation);
        // Build HTML
        $formData = $this->controller->entry_form->build_display($mode);
        echo $outcome;
        echo $formData;
        echo $supplement;
    }

    /**
     *  Create or update entry in database from entry page form fields in POST:
     *  use entry form definition from config db
     *  validate entry form information, submit to database if valid,
     *  and return JSON containing entry data with updated values
     *  and success/failure messages
     * @param array $data
     * @param string $mode
     * @param string $id
     */
    public function submit_entry_json(array $data, string $mode, string $id = '') {
        $resultData = $this->submit_entry_data($data, $mode, $id);
        $input_params = $resultData->input_params;
        $form_def = $resultData->form_def;
        $validation = $resultData->validation;
        $outcome = $resultData->outcome;
        $resultType = 'success';

        if (!$resultData->success)
        {
            $resultType= 'error';
        }

        // Get entry form object and use to to build and return array for form
        $this->controller->loadEntryFormLibrary($form_def->specs, $this->config_source);
        $this->process_submission_results($input_params, $form_def, $validation);
        $reportData = $this->controller->entry_form->build_entry_array($mode);
        $result = array(
            'result' => $resultType,
            'message' => $outcome
        );

        // Remove 'doc_' entries in the result report
        foreach ($reportData as $key => $value)
        {
            if (substr($key, 0, 4) === "doc_")
            {
                unset($reportData[$key]);
            }
        }

        \Config\Services::response()->setContentType("application/json");
        echo json_encode(array_merge($result, $reportData));
    }

    /**
     *  Create or update entry in database from entry page form fields (from POST data):
     *  use entry form definition from config db
     *  validate entry form information, submit to database if valid,
     *  and return data object containing updated values for entry form
     *  and success/failure messages
     * @param array $data
     * @param string $mode
     * @param string $id
     * @return \stdClass Object with necessary information to generate an appropriate response object
     */
    private function submit_entry_data(array $data, string $mode, string $id = ''): \stdClass {
        helper(['entry_page']);

        $this->controller->loadFormModel('na', $this->config_source);
        $form_def = $this->controller->form_model->get_form_def(array('fields', 'specs', 'rules', 'enable_spec'));
        $form_def->field_enable = $this->get_field_enable($form_def->enable_spec);

        $postData = $data;
        $preformat = new \App\Libraries\ValidationPreformat();
        $postData = $preformat->run($postData, $form_def->rules);

        $validation = \Config\Services::validation();
        $validation->setRules($form_def->rules);

        $resultData = new \stdClass();
        $input_params = new \stdClass();
        $outcome = '';
        try {
            $valid_fields = $validation->run($postData);

            // Get field values from validation object
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

            if (!$valid_fields) {
                throw new \Exception('There were validation errors');
            }

            // $msg is an output parameter of call_stored_procedure
            $msg = '';
            $this->call_stored_procedure($input_params, $form_def, $mode, $msg);

            // Everything worked - compose tidings of joy
            $message = 'Operation was successful';
            $resultData->message = $message;
            if (empty($msg)) {
                $outcome = $message;
            } else {
                // Define $outcome as "Operation was successful: message"
                $outcome = $message . ": " . $msg;
            }
            $resultData->success = true;
        } catch (\Exception $e) {
            // Something broke - compose expressions of regret
            $outcome = $e->getMessage();     // . " (page family: $this->tag)";
            $resultData->message = $outcome;

            // Use the 'mode' provided in the method call
            // Add or update the mode property of the input params
            if (empty($mode)) {
                $input_params->mode = 'retry';
            } else {
                $input_params->mode = $mode;
            }
            $resultData->success = false;
        }

        $resultData->outcome = $outcome;
        $resultData->input_params = $input_params;
        $resultData->form_def = $form_def;
        $resultData->validation = $validation;

        return $resultData;
    }

    // --------------------------------------------------------------------
    /**
     * Create the primary outcome message
     * @param string $message
     * @param string $option
     * @return string
     */
    private function outcome_msg($message, $option): string {
        return entry_outcome_message($message, $option, 'main_outcome_msg');
    }

    /**
     * Create the supplemental outcome message
     * @param string $message
     * @param string $option
     * @return string
     */
    private function supplement_msg($message, $option): string {
        return entry_outcome_message($message, $option, 'supplement_outcome_msg');
    }

    /**
     * Process submission output and prepare submission report data
     * @param \stdClass $input_params
     * @param \stdClass $form_def
     * @param ValidationInterface $validation
     */
    protected function process_submission_results(\stdClass $input_params, \stdClass $form_def, ValidationInterface $validation) {
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
            $fieldError = validation_error_format($validation, $field, '<span class="bad_clr">', '</span>');
            $fieldErrorPlain = $validation->getError($field);
            $this->controller->entry_form->set_field_error($field, $fieldError, $fieldErrorPlain);
        }
    }

    /**
     * Make post-submission links to list report and detail report
     * @param \stdClass $input_params
     */
    protected function get_post_submission_link(\stdClass $input_params) {
        $ps_link_specs = $this->controller->gen_model->get_post_submission_link_specs();
        $actions = $this->controller->gen_model->get_actions();
        return make_post_submission_links($this->controller->my_tag, $ps_link_specs, $input_params, $actions);
    }

    /**
     * Call a stored procedure
     * @param \stdClass $input_params
     * @param \stdClass $form_def
     * @param string $mode
     * @param string $msg Message returned by the stored procedure (output)
     */
    protected function call_stored_procedure(\stdClass $input_params, \stdClass $form_def, string $mode, &$msg) {
        $ok = $this->controller->loadSprocModel('entry_sproc', $this->config_source);
        if (!$ok) {
            throw new \Exception($this->controller->sproc_model->get_error_text());
        }

        $calling_params = $this->make_calling_param_object($input_params, $mode, $form_def->field_enable);
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
     * @param \stdClass $input_params
     */
    protected function update_input_params_from_stored_procedure_args(\stdClass $input_params) {
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
     * @param string $mode
     * @param mixed $field_enable
     * @return mixed
     */
    protected function make_calling_param_object($input_params, string $mode, $field_enable) {
        $calling_params = clone $input_params;
        $calling_params->mode = $mode;
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
