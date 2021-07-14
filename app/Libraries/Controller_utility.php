<?php
namespace App\Libraries;

// --------------------------------------------------------------------
// misc functions for controllers that use page libraries
// --------------------------------------------------------------------

class Controller_utility {

    // --------------------------------------------------------------------
    function message_box($heading, $message, $title = '') {
        $data['title'] = ($title) ? $title : $heading;
        $data['heading'] = $heading;
        $data['message'] = $message;
        $CI =& get_instance();
        echo view('message_box', $data);
    }

    /**
     * Load named library and initialize it with given config info
     * @param string $lib_name Library name, including list_report, detail_report, paging_filter, sorting_filter, column_filter, secondary_filter
     * @param string $config_name Config name, e.g. list_report
     * @param string $config_source Source, e.g. dataset, experiment, campaign
     * @param boolean $options Custom options flag
     * @return boolean
     */
    function load_lib($lib_name, $config_name, $config_source, $options = false) {
        $CI =& get_instance();
        if (property_exists($CI, $lib_name)) {
            return true;
        }
        // Load then initialize the model
        $libPath = "\App\Libraries\$lib_name";
        $CI->$lib_name = new $libPath();
        if ($options === false) {
            return $CI->$lib_name->init($config_name, $config_source);
        } else {
            return $CI->$lib_name->init($config_name, $config_source, $options);
        }
    }

    /**
     * Load named model (with given local name) and initialize it with given config info
     * @param string $model_name Module name, e.g. g_model, q_model
     * @param string $local_name Local name, e.g. gen_model for g_model; model for q_model
     * @param string $config_name Config type; typically na for g_model; list_report (or similar) for q_model
     * @param string $config_source Data source, e.g. dataset, experiment, ad_hoc_query
     * @return boolean
     */
    function load_mod($model_name, $local_name, $config_name, $config_source) {
        $CI =& get_instance();
        if (property_exists($CI, $local_name)) {
            return true;
        }
        // Dynamically load and initialize the model
        $CI->$local_name = model('App\\Models\\'.$model_name);
        return $CI->$local_name->init($config_name, $config_source);
    }

    /**
     * Check permissions
     * Verify (all):
     * - action is allowed for the page family
     * - user has at least basic access to website
     * - user has necessary permission if action is a restricted one
     * Present message box if access check fails and $output_message is true
     * @param string $action
     * @param boolean $output_message When true, update the message box with "Access Denied"
     * @return boolean
     */
    function check_access($action, $output_message = true) {
        $CI =& get_instance();
        helper('user');
        $user = get_user();

        $this->load_mod('g_model', 'gen_model', 'na', $CI->my_tag);

        if ($CI->gen_model->error_text) {
            if ($output_message) {
                $this->message_box('Error', $CI->gen_model->error_text);
            }
            return false;
        }

        $result = $CI->gen_model->check_permission($user, $action, $CI->my_tag);

        if ($result === true) {
            return true;
        } else {
            if ($output_message) {
                $this->message_box('Access Denied', $result);
            }
            return false;
        }
    }
}
?>
