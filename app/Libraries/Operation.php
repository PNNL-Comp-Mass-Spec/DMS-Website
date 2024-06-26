<?php
namespace App\Libraries;

/**
 *  'operations' style stored procedure functions
 */
class Operation {

    private $config_source = '';

    // --------------------------------------------------------------------
    function __construct() {

    }

    /**
     * Define $config_source
     * @param type $config_name
     * @param type $config_source
     * @param type $controller
     */
    function init($config_name, $config_source, $controller) {
        $this->config_source = $config_source;

        $this->controller = $controller;
    }

    /**
     * Calls given stored procedure for this page family using calling parameters
     * derived from the sproc args definition for the stored procedure in config db
     * (and looks for a 'command' field in POST which is set to sproc arg 'mode').
     * See also https://prismwiki.pnl.gov/wiki/DMS_Config_DB_Help_detail_report_commands#Command_Types
     * @param type $sproc_name
     * @return \stdClass A response object containing return value and message from sproc
     * @throws exception
     */
    function internal_operation($sproc_name) {
        $config_name = $sproc_name;
        $response = new \stdClass();

        try {
            // Init sproc model
            $ok = $this->controller->load_mod('S_model', 'sproc_model', $config_name, $this->config_source);
            if (!$ok) {
                throw new \Exception($this->controller->sproc_model->get_error_text());
            }

            // Get sproc fields and use them to make validation field definitions
            $fields = $this->controller->sproc_model->get_sproc_fields();
            $rules = array();
            foreach ($fields as $field) {
                $rule = array();
                $rule['field'] = $field;
                $rule['label'] = $field;
                $rule['rules'] = 'trim'; // someday: rule to require presence of arg?
                $rules[] = $rule;
            }

            // Make validation object and use it to
            // get field values from POST and validate them
            // For more info, see https://prismwiki.pnl.gov/wiki/DMS_Config_DB_Help_detail_report_commands#Command_Types

            helper('form');
            $request = \Config\Services::request();
            $postData = $request->getPost();
            // NOTE: if $postData is empty, check the URL; if the URL ends with '/', POST data is dropped.
            $preformat = new \App\Libraries\ValidationPreformat();
            $postData = $preformat->run($postData, $rules);

            $validation =  \Config\Services::validation();
            $validation->setRules($rules);
            $valid_fields = $validation->run($postData);

            // Get field values from validation object into an object
            // that will be used for calling stored procedure
            // and also putting values back into entry form HTML
            helper('user');
            $calling_params = new \stdClass();
            foreach ($fields as $field) {
                $calling_params->$field = $postData[$field];
            }

            $calling_params->mode = ($request->getPost('mode')) ? $request->getPost('mode') : $request->getPost('command');
            $calling_params->callingUser = get_user();
            $calling_params->message = '';

            // Call the stored procedure
            $success = $this->controller->sproc_model->execute_sproc($calling_params);
            if (!$success) {
                throw new \Exception($this->controller->sproc_model->get_error_text());
            }

            $response->result = $this->controller->sproc_model->get_parameters()->retval;
            $response->message = $this->controller->sproc_model->get_parameters()->message;
        } catch (\Exception $e) {
            $response->result = -1;
            $response->message = $e->getMessage();
        }
        return $response;
    }

    /**
     * Get params that sproc was called with, including changes passed back from sproc
     * @return type
     */
    function get_params() {
        return $this->controller->sproc_model->get_parameters();
    }
}
?>
