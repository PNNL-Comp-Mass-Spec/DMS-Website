<?php
namespace App\Libraries;

/**
 * Supports editing grid data
 */
class Grid_data {

    private \App\Controllers\BaseController $controller;
    private $config_source = '';
    private $config_name = '';

    // --------------------------------------------------------------------
    function __construct() {

    }

    /**
     * Initialize the grid data
     * @param string $config_name
     * @param string $config_source
     * @param \App\Controllers\BaseController $controller
     */
    function init(string $config_name, string $config_source, \App\Controllers\BaseController $controller) {
        $this->config_source = $config_source;
        $this->config_name = $config_name;

        $this->controller = $controller;
    }

    /**
     * Get data for the grid ---------------------- April 2025 - not currently used anywhere
     * @param string $sql
     * @param array|bool $paramArray
     */
    function get_query_data(string $sql, $paramArray) {
        $response = new \stdClass();
        try {
            $db = \Config\Database::connect();
            $this->controller->updateSearchPath($db);
            $result = $db->query($sql);
            if (!$result) {
                $currentTimestamp = date("Y-m-d");
                throw new \Exception("Error querying database; see writable/logs/log-$currentTimestamp.php");
            }
            $columns = array();
            foreach ($result->getFieldData() as $field) {
                $columns[] = $field->name;
            }
            $response->result = 'ok';
            $response->message = '';
            $response->columns = $columns;
            $response->rows = $result->getResultArray();
        } catch (\Exception $e) {
            $response->result = 'error';
            $response->message = 'get_query_data: ' . $e->getMessage();
        }
        echo json_encode($response);
    }

    /**
     * Get data from a stored procedure
     * @param array|bool $paramArray
     * @param string $config_name
     * @return \stdClass
     * @throws \Exception
     */
    function get_sproc_data($paramArray, string $config_name = '') {
        if (!$config_name) {
            $config_name = $this->config_name;
        }

        helper(['user', 'url']);
        $response = new \stdClass();
        try {
            // Init sproc model
            $ok = $this->controller->loadSprocModel($config_name, $this->config_source);
            if (!$ok) {
                throw new \Exception($this->controller->sproc_model->get_error_text());
            }

            $fields = $this->controller->sproc_model->get_sproc_fields();
            $paramObj = $this->get_input_values($fields, $paramArray);
            $calling_params = $this->controller->sproc_model->get_calling_args($paramObj);

            $success = $this->controller->sproc_model->execute_sproc($calling_params);
            if (!$success) {
                throw new \Exception($this->controller->sproc_model->get_error_text());
            }

            $response->result = 'ok';
            $response->message = $this->controller->sproc_model->get_parameters()->message;

            $response->columns = $this->controller->sproc_model->get_col_names();
            $response->rows = $this->controller->sproc_model->get_rows();
        } catch (\Exception $e) {
            $response->result = 'error';
            $response->message = 'get_sproc_data: ' . $e->getMessage();
        }
        return $response;
    }

    // --------------------------------------------------------------------
    private function get_input_values($fields, $paramArray) {
        if ($paramArray === false) {
            $paramArray = array();
        }
        $paramObj = new \stdClass();
        foreach ($fields as $field) {
            $paramObj->$field = (array_key_exists($field, $paramArray)) ? $paramArray[$field] : '';
        }
        return $paramObj;
    }
}
?>
