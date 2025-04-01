<?php
namespace App\Libraries;

/**
 * Generate a list report from queries defined in the utility_queries table
 */
class List_report_ah extends List_report {

    // --------------------------------------------------------------------
    function __construct() {
        parent::__construct();
    }

    /**
     * Make a list report page
     * (override of base class function)
     * @param string $mode
     */
    function list_report($mode) {
        //Ensure a session is initialized
        $session = \Config\Services::session();

        helper(['form', 'menu', 'link_util', 'url']);

        $this->controller->loadGeneralModel($this->config_name, $this->config_source);
        $this->controller->loadLinkModel($this->config_name, $this->config_source);

        // Clear total rows cache in model to force getting value from database
        $this->controller->loadDataModel($this->config_name, $this->config_source);
        $this->controller->data_model->clear_cached_total_rows();

        // If there were extra segments for list report URL,
        // convert them to primary filter field values and cache those
        // and redirect back to ourselves without the trailing URL segments
        $all_segs = getCurrentUriDecodedSegments();
        $end_of_root_segs = array_search($mode, $all_segs) + 1;
        $root_segs = array_slice($all_segs, 0, $end_of_root_segs);
        $segs = array_slice($all_segs, $end_of_root_segs);
        if (!empty($segs)) {
            $primary_filter_specs = $this->controller->data_model->get_primary_filter_specs();
            $this->set_pri_filter_from_url_segments($segs, $primary_filter_specs);
            redirect()->to(site_url(implode('/', $root_segs)));
        }

        $data['tag'] = $this->tag;
        $data['my_tag'] = $this->controller->my_tag;
        $data['title'] = $this->controller->gen_model->get_page_label('', $mode);

        // Get stuff related to list report optional features
        $data['loading'] = ($mode === 'search') ? 'no_load' : '';
        $data['list_report_cmds'] = ''; ///$this->controller->gen_model->get_param('list_report_cmds');
        $data['is_ms_helper'] = $this->controller->gen_model->get_param('is_ms_helper');
        $data['has_checkboxes'] = $this->controller->gen_model->get_param('has_checkboxes');
        $data['ops_url'] = ''; ///site_url($this->controller->gen_model->get_param('list_report_cmds_url'));

        //$data['check_access'] = [$this->controller, 'check_access'];
        //$data['choosers'] = $this->controller->getChoosers();

        $data['nav_bar_menu_items'] = set_up_nav_bar('List_Reports', $this->controller);
        echo view('main/list_report', $data);
    }

    /**
     * Returns HTML displaying the list report data rows
     * for inclusion in list report page
     * (override of base class function)
     * @param type $option
     * @category AJAX
     */
    function report_data($option = 'rows') {
        // Preemptively load the hotlinks model from the ad hoc config db
        // to prevent parent from loading it from general_param table,
        // then let parent handle it
        $this->controller->loadLinkModel($this->config_name, $this->config_source);
        parent::report_data($option);
    }

    // --------------------------------------------------------------------
    function set_up_data_query() {
        $this->set_up_list_query();
    }
}
?>
