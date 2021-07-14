<?php
namespace App\Controllers;

class Unit_Test extends BaseController {
    // --------------------------------------------------------------------
    function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        session_start();
        $this->helpers = array_merge($this->helpers, ['url', 'string']);
        $this->color_code = $this->config->item('version_color_code');
    }
    // --------------------------------------------------------------------
    function index()
    {
            echo 'Yo!';
    }
    // --------------------------------------------------------------------
    function run()
    {
        $segs = array_slice($this->uri->segment_array(), 2);
        $testFile = (count($segs) == 0)? 'test' : $segs[0] . '_test';

        $data['title'] = "DMS JavaScript Unit Tests";
        $data['testFile'] = $testFile;

        $this->load->vars($data);
        $this->load->view('unit_tests/test_frame');
    }
}
?>
