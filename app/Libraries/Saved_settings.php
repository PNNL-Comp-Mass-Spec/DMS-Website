<?php
namespace App\Libraries;

class Saved_settings {

    // --------------------------------------------------------------------
    function __construct($controller) {
        $this->controller =& $controller;
    }

    /**
     * Clears cached session variables
     * (intended for calling via AJAX)
     * @param type $page_type
     * @param type $config_source
     */
    function defaults($page_type, $config_source) { //'Param_Pages''list_report_sproc'   'list_report'
        //Ensure a session is initialized
        $session = \Config\Services::session();

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
            $this->controller->load_mod('Q_model', 'data_model', $config_name, $config_source);
            $primary_filter_specs = $this->controller->data_model->get_primary_filter_specs();
            $this->controller->data_model->clear_cached_state();

            // Primary filter
            $this->controller->primary_filter = new \App\Libraries\Primary_filter();
            $this->controller->primary_filter->init($config_name, $config_source, $this->controller, $primary_filter_specs);
            $this->controller->primary_filter->clear_cached_state();

            // Secondary filter
            $this->controller->load_lib('Secondary_filter', $config_name, $config_source);
            $this->controller->secondary_filter->clear_cached_state();
        } else
        if ($page_type == 'Param_Pages') {
            $this->controller->load_mod('S_model', 'sproc_model', $config_name, $config_source);
            $this->controller->sproc_model->clear_cached_state();
        }

        // Paging filter
        $this->controller->load_lib('Paging_filter', $config_name, $config_source);
        $this->controller->paging_filter->clear_cached_state();

        $options = array("PersistSortColumns" => true);

        // Sorting filter
        $this->controller->load_lib('Sorting_filter', $config_name, $config_source, $options);
        $this->controller->sorting_filter->clear_cached_state();

        // Column filter (unused)
        // $this->controller->load_lib('Column_filter', $config_name, $config_source);
        // $col_filter = $this->controller->column_filter->clear_cached_state();

        echo "Saved preferences were cleared";
    }
}
?>
