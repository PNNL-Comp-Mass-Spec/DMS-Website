<?php
//require("Base_controller.php");

class Ers extends CI_Controller {

	var $my_tag = "ers";

	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
	}
	// --------------------------------------------------------------------
	function index()
	{
	}

	// --------------------------------------------------------------------
	function proposals()
	{
		$this->load->library('table');
		$ersDB = $this->load->database('ers', TRUE);

		// get list of proposals from ers
		// PROPOSAL_ID	TITLE	DESCRIPTION
		$sql = 'select PROPOSAL_ID, TITLE, DESCRIPTION from VW_ALL_ACTIVE_PROPOSALS';
		$result = $ersDB->query($sql);
		//
		if(!$result) {
			echo "No results found";
			return;
		}
		echo $this->table->generate($result);
	}

	// --------------------------------------------------------------------
	function users()
	{
		$this->load->library('table');
		$ersDB = $this->load->database('ers', TRUE);

		// get list of users for proposals from ers PROPOSAL_ID, HANFORD_ID
		// PROPOSAL_ID	TITLE	DESCRIPTION	HANFORD_ID
		$sql = 'select * from VW_USERS_ACTIVE_PROPOSALS';
		$result = $ersDB->query($sql);
		//
		if(!$result) {
			echo "No results found";
			return;
		}
		echo $this->table->generate($result);
	}

	// --------------------------------------------------------------------
	function dms_proposals()
	{
		$this->load->library('table');
		$dmsDB = $this->load->database('default', TRUE);

		// get list of proposals from ers
		$sql = 'SELECT PROPOSAL_ID, TITLE FROM T_EUS_Proposals';
		$result = $dmsDB->query($sql);
		//
		if(!$result) {
			echo "No results found";
			return;
		}
		echo $this->table->generate($result);
	}

	// --------------------------------------------------------------------
	function dms_users()
	{
		$this->load->library('table');
		$dmsDB = $this->load->database('default', TRUE);

		// get list of proposals from ers
		$sql = 'SELECT  [User ID], [User Name] FROM V_EUS_Users_ID';
		$result = $dmsDB->query($sql);
		//
		if(!$result) {
			echo "No results found";
			return;
		}
		echo $this->table->generate($result);
	}

	// --------------------------------------------------------------------
	function new_proposals()
	{
		$this->load->library('table');

		// get list of proposals from ers
		$dmsDB = $this->load->database('default', TRUE);
		$sql = 'SELECT PROPOSAL_ID, TITLE FROM T_EUS_Proposals';
		$result = $dmsDB->query($sql);
		//
		if(!$result) {
			echo "No DMS results found";
			return;
		}
		$dms_proposals = array();
		foreach($result->result() as $row) {
			$dms_proposals[$row->PROPOSAL_ID] = $row->TITLE;
		}

		// get list of proposals from ers
		$ersDB = $this->load->database('ers', TRUE);
		$sql = 'select PROPOSAL_ID, TITLE, DESCRIPTION from VW_ALL_ACTIVE_PROPOSALS ORDER BY PROPOSAL_ID DESC';
		$result = $ersDB->query($sql);
		//
		if(!$result) {
			echo "No ERS results found";
			return;
		}
		$ers_proposals = array();
		foreach($result->result() as $row) {
			$ers_proposals[$row->PROPOSAL_ID] = $row->TITLE;
		}
//		echo $this->table->generate($ers_proposals);
		echo "<table border='1'>";
		foreach($ers_proposals as $id => $title) {
			$s = "";
			if(array_key_exists($id, $dms_proposals)) {
				$s = $dms_proposals[$id];
			}
			echo "<tr><td>$id</td><td>$title</td><td>$s</td></tr>\n";
		}
		echo "</table>";

	}

}