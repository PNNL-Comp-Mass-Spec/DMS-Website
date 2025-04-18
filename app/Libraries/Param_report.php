<?php
namespace App\Libraries;

use \CodeIgniter\Validation\ValidationInterface;

// --------------------------------------------------------------------
// Param report (stored procedure based list report) section
// --------------------------------------------------------------------

class Param_report {

    private \App\Controllers\BaseController $controller;
    private $config_source = '';
    private $config_name = '';
    private $tag = '';
    private $title = '';
    private $current_sorting_filter_values = null;

    // --------------------------------------------------------------------
    function __construct() {

    }

    // --------------------------------------------------------------------
    // List report page section
    // --------------------------------------------------------------------
    function init($config_name, $config_source, $controller) {
        $this->config_name = $config_name;
        $this->config_source = $config_source;

        $this->controller = $controller;
        $this->tag = $this->controller->my_tag;
        $this->title = $this->controller->my_title;
    }

    /**
     * Sets up a page that contains an entry form defined by the
     * E_model for the config db which will be used to get data
     * rows in HTML via and AJAX call to the param_data function.
     */
    function param() {
        // General specifications for page family
        $this->controller->loadGeneralModel('na', $this->config_source);

        // Entry form
        $this->controller->loadFormModel('na', $this->config_source);
        $form_def = $this->controller->form_model->get_form_def(array('fields', 'specs'));

        $this->controller->loadEntryFormLibrary($form_def->specs, $this->config_source);

        // Get initial field values (from url segments) and merge them with form object
        $segs = array_slice(getCurrentUriDecodedSegments(), 2);
        helper(['entry_page']);
        $initial_field_values = get_values_from_segs($form_def->fields, $segs);
        foreach ($initial_field_values as $field => $value) {
            $this->controller->entry_form->set_field_value($field, $value);
        }
        $hdr = (empty($form_def->specs)) ? '' : "<span class='filter_label'>Search Parameters</span>\n";
        $data['form'] = $hdr . $this->controller->entry_form->build_display("add");

        $data['title'] = $this->controller->gen_model->get_page_label($this->title, 'param');
        $data['tag'] = $this->tag;
        $data['my_tag'] = $this->controller->my_tag;

        // Get stuff related to list report optional features
//      $data['loading'] = ($mode === 'search')?'no_load':'';
        $data['list_report_cmds'] = $this->controller->gen_model->get_param('list_report_cmds');
        $data['is_ms_helper'] = $this->controller->gen_model->get_param('is_ms_helper');
        $data['has_checkboxes'] = $this->controller->gen_model->get_param('has_checkboxes');
        $data['ops_url'] = site_url($this->controller->gen_model->get_param('list_report_cmds_url'));

        $data['check_access'] = [$this->controller, 'check_access'];
        $data['choosers'] = $this->controller->getChoosers();

        helper(['menu', 'link_util']);
        $data['nav_bar_menu_items'] = set_up_nav_bar('Param_Pages', $this->controller);
        echo view('main/param_report', $data);
    }

    /**
     * Returns HTML data row table of data returned by stored procedure.
     * This uses the stored procedure defined by the 'list_report_sproc'
     * parameter in the general_params table of the config db and expects
     * POST data from a form defined by the form_fields table of the config db
     * (via the E_model).
     * @category AJAX
     */
    function param_data() {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        helper('user');

        $message = $this->get_data_rows_from_sproc();
        if ($message) {
            echo "<div id='data_message' >$message</div>";
            return;
        }
        $rows = $this->get_filtered_param_report_rows();
        if (empty($rows)) {
            echo "<div id='data_message' >No rows found</div>";
        } else {
            $this->controller->loadLinkModel('na', $this->config_source);

            $column_filter = $this->controller->getLibrary('Column_filter', $this->config_name, $this->config_source);
            $col_filter = $column_filter->get_current_filter_values();

            $cell_presentation = new \App\Libraries\Cell_presentation();
            $cell_presentation->init($this->controller->link_model->get_list_report_hotlinks());

            // (someday) roll the date fix into a function shareable with export_param
            $col_info = $this->controller->sproc_model->get_column_info();
            $cell_presentation->fix_datetime_and_decimal_display($rows, $col_info);
            $cell_presentation->set_col_filter($col_filter);

            $current_sorting_filter_values = $this->current_sorting_filter_values;
            $data['rows'] = $rows;
            $data['row_renderer'] = $cell_presentation;
            $data['column_header'] = $cell_presentation->make_column_header($rows, $current_sorting_filter_values);

            helper(['text']);
            echo view('main/param_report_data', $data);
        }
    }

    /**
     * Get filtered data
     * @param bool $paging
     * @return array
     */
    private function get_filtered_param_report_rows(bool $paging = true): array {
        $paging_filter = $this->controller->getLibrary('Paging_filter', $this->config_name, $this->config_source);
        if ($paging) {
            $current_paging_filter_values = $paging_filter->get_current_filter_values();
        } else {
            $current_paging_filter_values = array();
        }

        $options = array("PersistSortColumns" => true);

        $sorting_filter = $this->controller->getLibrary('Sorting_filter', $this->config_name, $this->config_source, $options);
        $current_sorting_filter_values = $sorting_filter->get_current_filter_values();
        $this->current_sorting_filter_values = $current_sorting_filter_values;

        return $this->controller->sproc_model->get_filtered_rows($current_sorting_filter_values, $current_paging_filter_values);
    }

    /**
     * Get rowset from sproc specified by config_name/config_source
     * using parameters delivered from the entry form specified
     * by config_name/config_source, and set up controller for
     * call to $this->controller->sproc_model->get_rows();
     * @return string
     * @throws \Exception
     */
    private function get_data_rows_from_sproc(): string {
        helper('form');

        // Get specifications for the entry form
        // Used for submission into POST and to be returned as HTML
        $this->controller->loadFormModel('na', $this->config_source);
        $form_def = $this->controller->form_model->get_form_def(array('fields', 'rules'));

        $calling_params = new \stdClass();
        $validation = null;
        if (empty($form_def->fields)) {
            $valid_fields = true;
        } else {
            // Make validation object and use it to
            // get field values from POST and validate them
            $request = \Config\Services::request();
            $postData = $request->getPost();
            $preformat = new \App\Libraries\ValidationPreformat();
            $postData = $preformat->run($postData, $form_def->rules);

            $validation =  \Config\Services::validation();
            $validation->setRules($form_def->rules);
            $valid_fields = $validation->run($postData);

            // Get field values from validation object into an object
            // that will be used for calling stored procedure
            // and also putting values back into entry form HTML
            foreach ($form_def->fields as $field) {
                $calling_params->$field = $postData[$field];
            }
        }

        // Parameters needed by stored procedure that are not in entry form specs
        $calling_params->mode = \Config\Services::request()->getPost('entry_cmd_mode');
        $calling_params->callingUser = get_user();

        $message = '';
        try {
            if (!$valid_fields) {
                throw new \Exception('There were validation errors: ' . validation_errors_format($validation, '<span class="bad_clr">', '</span>'));
            }

            // Call stored procedure
            $ok = $this->controller->loadSprocModel($this->config_name, $this->config_source);
            if (!$ok) {
                throw new \Exception($this->controller->sproc_model->get_error_text());
            }

            $success = $this->controller->sproc_model->execute_sproc($calling_params);
            if (!$success) {
                throw new \Exception($this->controller->sproc_model->get_error_text());
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return $message;
    }

    /**
     * Create HTML displaying the SQL behind the data or the URL for deep-linking to the page
     * @param string $what_info
     * @category AJAX
     */
    function param_info(string $what_info) {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        $this->get_filter_values();

        switch ($what_info) {
            case "sql":
                echo $this->controller->data_model->get_sql("filtered_and_sorted");
                break;
            case "url":
                $filters = $this->get_filter_values();
                echo $this->dump_filters($filters, $this->controller->my_tag);
                break;
        }
    }

    /**
     * Convert the filters into a string for use by report_info
     * @param array $filters
     * @param string $tag
     * @return string
     */
    private function dump_filters(array $filters, string $tag) {
        $s = "";
        helper(['wildcard_conversion']);

        // Dump primary filter to segment list
        // Replace spaces with %20
        // Trim leading and trailing whitespace
        $pf = array();
        foreach ($filters as $f) {
            $x = "";
            if (array_key_exists("value", $f)) {
                $x = $f["value"];
            } else {
                $x = $x ? $x : "-";
            }
            $pf[] = str_replace(" ", "%20", encode_special_values(trim($x)));
        }
        $s .= site_url("$tag/param/" . implode("/", $pf));

        $dateFilters = array("LaterThan", "EarlierThan");

        return $s;
    }

    /**
     * Get param report search filters
     * @return array Filter settings
     */
    protected function get_filter_values() {
        // It all starts with a model
        $this->controller->loadFormModel('na', $this->config_source);
        $form_def = $this->controller->form_model->get_form_def(array('specs', 'fields'));

        // Search filter
        $current_search_filter_values = array();
        if (!empty($form_def->specs)) {
            foreach ($form_def->specs as $field => $spec) {
                // The form field type may contain several keywords specified by a vertical bar
                $fieldTypes = explode('|', $spec['type']);

                if (!in_array('hidden', $fieldTypes) && !in_array('non-edit', $fieldTypes)) {
                    $current_search_filter_values[] = $spec;
                }
            }
        }

        $filter_values = $this->get_current_filter_values_from_post($current_search_filter_values);
        if (!$filter_values) {
            $filter_values = $current_search_filter_values;
        }

        // Return filter settings
        // return $current_search_filter_values;
        return $filter_values;
    }

    /**
     * Get current values for secondary filter if present in POST.
     * Otherwise return false
     * @param array $filter_specs
     * @return array|bool
     */
    private function get_current_filter_values_from_post(array $filter_specs) {
        // (someday) smarter extraction of primary filter values from POST:
        // There may be other items in the POST not relevant to primary filter.
        // Maybe we can check for the presence of any scalars that begin with "pf_"
        if (!empty($_POST)) {
            foreach (array_keys($filter_specs) as $id) {
                $filterVal = filter_input(INPUT_POST, $id, FILTER_SANITIZE_SPECIAL_CHARS);
                // Check for $filterVal being empty; cannot use empty() since '0' is considered empty
                if ($filterVal !== '') {
                    // Check for encoded tabs (introduced by filter_input) and change them back to true tab characters
                    // Also, trim whitespace
                    $filter_specs[$id]["value"] = trim(str_replace('&#9;', "	", $filterVal));
                }
            }
            return $filter_specs;
        } else {
            return false;
        }
    }

    /**
     * Returns HTML for the paging display and control element
     * for inclusion in param report pages
     * @category AJAX
     */
    function param_paging() {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        helper(['link_util']);

        // Current paging settings
        $paging_filter = $this->controller->getLibrary('Paging_filter', $this->config_name, $this->config_source);
        $current_paging_filter_values = $paging_filter->get_current_filter_values();

        // Model to get current row info
        $this->controller->loadSprocModel($this->config_name, $this->config_source);

        // Pull together info necessary to do paging displays and controls
        // and use it to set up a pager object
        $total_rows = $this->controller->sproc_model->get_total_rows();
        $per_page = $current_paging_filter_values['qf_rows_per_page'];
        $first_row = $current_paging_filter_values['qf_first_row'];

        // Make HTML using pager
        $preferences = $this->controller->getPreferences();
        $list_report_pager = new \App\Libraries\List_report_pager();
        $s = '';
        $list_report_pager->set($first_row, $total_rows, $per_page);
        $pr = $list_report_pager->create_links();
        $ps = $list_report_pager->create_stats($this->controller);
        $s .= "<span class='LRepPager'>$ps</span>";
        $s .= "<span class='LRepPager'>$pr</span>";
        echo $s;
    }

    // --------------------------------------------------------------------
    // AJAX
    function param_filter() {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        // Call stored procedure
        $this->controller->loadSprocModel($this->config_name, $this->config_source);
        $cols = $this->controller->sproc_model->get_col_names();

        $paging_filter = $this->controller->getLibrary('Paging_filter', $this->config_name, $this->config_source);
        $current_paging_filter_values = $paging_filter->get_current_filter_values();

        $options = array("PersistSortColumns" => true);

        $sorting_filter = $this->controller->getLibrary('Sorting_filter', $this->config_name, $this->config_source, $options);
        $current_sorting_filter_values = $sorting_filter->get_current_filter_values();

        $column_filter = $this->controller->getLibrary('Column_filter', $this->config_name, $this->config_source);
        $col_filter = $column_filter->get_current_filter_values();

        helper('form');
        helper(['filter', 'link_util']);
        make_param_filter($cols, $current_paging_filter_values, $current_sorting_filter_values, $col_filter);
    }

    /**
     * Export a param report
     * @param string $format
     */
    function export_param(string $format) {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        helper(['user', 'export']);

        $message = $this->get_data_rows_from_sproc();
        if ($message) {
            echo $message;
            return;
        }

        $rows = $this->get_filtered_param_report_rows(false);

        // (someday) roll the date fix into a function shareable with param_data
        $cell_presentation = new \App\Libraries\Cell_presentation();
        $col_info = $this->controller->sproc_model->get_column_info();
        $cell_presentation->fix_datetime_and_decimal_display($rows, $col_info);

        $column_filter = $this->controller->getLibrary('Column_filter', $this->config_name, $this->config_source);
        $col_filter = $column_filter->get_current_filter_values();

        if (count($rows) === 0) {
            echo '<p>The table appears to have no data.</p>';
        } else {
            switch ($format) {
                case 'excel':
                    export_to_excel($rows, $this->tag, $col_filter);
                    break;
                case 'tsv':
                    export_to_tab_delimited_text($rows, $this->tag, $col_filter);
                    break;
                case 'json':
                    \Config\Services::response()->setContentType("application/json");
                    echo json_encode($rows);
                    break;
            }
        }
    }
}
?>
