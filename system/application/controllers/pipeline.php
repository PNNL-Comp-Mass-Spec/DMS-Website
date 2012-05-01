<?php

class pipeline extends Controller {


	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
	}

	// --------------------------------------------------------------------
	function index()
	{
		$this->load->helper(array('url','html'));

		echo heading('Mini-Pipeline Page links', 3);

		$links = array();

		$links[] = anchor('pipeline_script/report', 'Scripts');
		$links[] = anchor('pipeline_jobs/report', 'Jobs');
		$links[] = anchor('pipeline_job_steps/report', 'Job steps');
		$links[] = anchor('pipeline_step_tools/report', 'Step Tools');
		$links[] = anchor('pipeline_local_processors/report', 'Local Processors');

		echo ul($links);
	}

}
?>