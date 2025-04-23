<?php
namespace App\Libraries;

class Saved_settings {

    private \App\Controllers\BaseController $controller;

    // --------------------------------------------------------------------
    function __construct($controller) {
        $this->controller =& $controller;
    }

    /**
     * Clears cached session variables
     * (intended for calling via AJAX)
     * @param string $page_type
     * @param string $config_source
     */
    function defaults(string $page_type, string $config_source) { //'Param_Pages''list_report_sproc'   'list_report'
        //Ensure a session is initialized
        $session = service('session');

        if ($page_type == 'List_Reports') {
            $config_name = 'list_report';
        } else
        if ($page_type == 'Param_Pages') {
            $config_name = 'list_report_sproc';
        } else {
            echo "Unrecognized page type '$page_type'";
            return;
        }

        if ($page_type == 'List_Reports') {
            // It all starts with a model
            $this->controller->loadDataModel($config_name, $config_source);
            $primary_filter_specs = $this->controller->data_model->get_primary_filter_specs();
            $this->controller->data_model->clear_cached_state();

            // Primary filter
            $primary_filter = $this->controller->getLibrary('Primary_filter', $config_name, $config_source, $primary_filter_specs);
            $primary_filter->clear_cached_state();

            // Secondary filter
            $secondary_filter = $this->controller->getLibrary('Secondary_filter', $config_name, $config_source);
            $secondary_filter->clear_cached_state();
        } else
        if ($page_type == 'Param_Pages') {
            $this->controller->loadSprocModel($config_name, $config_source);
            $this->controller->sproc_model->clear_cached_state();
        }

        // Paging filter
        $paging_filter = $this->controller->getLibrary('Paging_filter', $config_name, $config_source);
        $paging_filter->clear_cached_state();

        $options = array("PersistSortColumns" => true);

        // Sorting filter
        $sorting_filter = $this->controller->getLibrary('Sorting_filter', $config_name, $config_source, $options);
        $sorting_filter->clear_cached_state();

        // Column filter (unused)
        // $column_filter = $this->controller->getLibrary('Column_filter', $config_name, $config_source);
        // $col_filter = $column_filter->clear_cached_state();

        echo "Saved preferences were cleared";
    }
}
?>
