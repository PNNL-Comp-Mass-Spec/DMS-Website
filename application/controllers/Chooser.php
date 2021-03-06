<?php

class Chooser extends CI_Controller {

    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();
        $this->load->helper(array('url', 'string', 'form'));
    }

    // --------------------------------------------------------------------
    function index()
    {
        echo "CI Rocks!";
    }

    /**
     *  This returns HTML for a drop-down selector and suitable options
     *  for the specified chooser_name.  It is suitable for AJAX
     * @param type $target_field_name
     * @param type $chooser_name
     * @param type $mode
     */
    function get_chooser($target_field_name, $chooser_name, $mode)
    {
        $this->load->model('dms_chooser', 'choosers');
        echo $this->choosers->get_chooser($target_field_name, $chooser_name, $mode);
    }

    // --------------------------------------------------------------------
    function get_chooser_list()
    {
        $this->load->model('dms_chooser', 'choosers');
        echo "<table>\n";
        foreach($this->choosers->get_chooser_names() as $chooser_name) {
            $url = site_url()."chooser/get_chooser/preview/$chooser_name/replace";
            echo "<tr>";
            echo "<td><a href='$url'>$chooser_name</a></td>\n";
            echo "<td>".$this->choosers->get_chooser('preview', $chooser_name, 'replace')."</td>";
            echo "</tr>";
        }
        echo "</table>\n";
    }

    /**
     * This returns list of selections for the specified chooser_name.  It is suitable for AJAX
     * @param type $chooser_name
     */
    function get_choices($chooser_name)
    {
        $this->load->model('dms_chooser', 'choosers');
        $x = array_keys( $this->choosers->get_choices($chooser_name) );
        echo json_encode($x);
    }

    /**
     * This returns list of selections for the specified chooser_name.  It is suitable for AJAX
     * @param type $chooser_name
     * @param type $filter_value
     */
    function json($chooser_name, $filter_value = '')
    {
        if(!$filter_value) {
            $filter_value = $this->input->post('filter_values');
        }
        $this->load->model('dms_chooser', 'choosers');
        $x = $this->choosers->get_filtered_choices($chooser_name, $filter_value);
        echo json_encode($x);
    }

}
