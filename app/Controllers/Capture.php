<?php
namespace App\Controllers;

class Capture extends BaseController {
    // --------------------------------------------------------------------
    function index()
    {
        helper(['url','html']);

        echo heading('Capture-capture Page links', 3);

        $links = array();

        $links[] = anchor('capture_script/report', 'Scripts');
        $links[] = anchor('capture_jobs/report', 'Jobs');
        $links[] = anchor('capture_job_steps/report', 'Job steps');
        $links[] = anchor('capture_step_tools/report', 'Step Tools');
        $links[] = anchor('capture_local_processors/report', 'Local Processors');

        echo ul($links);
    }
}
?>
