<?php
namespace App\Controllers;

class Unit_Test extends BaseController {

    function __construct()
    {
        $this->helpers = array_merge($this->helpers, ['url', 'text']);
    }

    /**
     * CodeIgniter 4 Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //Ensure a session is initialized
        $session = \Config\Services::session();
    }

    // --------------------------------------------------------------------
    function index()
    {
            echo 'Yo!';
    }
    // --------------------------------------------------------------------
    function run()
    {
        $segs = array_slice($this->request->getUri()->getSegments(), 2);
        $testFile = (count($segs) == 0)? 'test' : $segs[0] . '_test';

        $data['title'] = "DMS JavaScript Unit Tests";
        $data['testFile'] = $testFile;

        echo view('unit_tests/test_frame', $data);
    }
}
?>
