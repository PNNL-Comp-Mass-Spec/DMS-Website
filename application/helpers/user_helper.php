<?php  
	if (!defined('BASEPATH')) {
		exit('No direct script access allowed');
	}

	// --------------------------------------------------------------------
	function get_user()
	{
		$user = '(unknown)';
		if(isset($_SERVER["REMOTE_USER"])) {
			$user = str_replace ('@PNL.GOV', '', $_SERVER["REMOTE_USER"]);
		}
		return $user;
	}
