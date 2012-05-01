<?php
require("base_controller.php");

class notification extends Base_controller {
	// --------------------------------------------------------------------
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "notification";
		$this->my_title = "Notification";
	}

	// --------------------------------------------------------------------
	function _get_notification_info()
	{
		$users = array();
		// get  information
		$sql = '';
		$sql .= 'SELECT Event, Name, Link, Campaign, Role, EventTypeID, [#EntityType], [#PRN], [User], User_ID, Entity,  Email ';
		$sql .= 'FROM V_Notification_Message_By_Registered_Users ';
		$sql .= 'WHERE Entered > DATEADD(HOUR, - 24, GETDATE()) ';
		$sql .= 'ORDER BY [#PRN], [#EntityType], EventTypeID, Entity ';

		$this->load->database();
		$result = $this->db->query($sql);
		if(!$result) {
			return $users();
		}

		$email = array();
		$rows = $result->result_array();
		foreach($rows as $row) {
			$prn = $row['#PRN'];
			if(!array_key_exists($prn, $users)) {
				$obj = new stdClass();
				$obj->user = $prn;
				$obj->user_name = $row['User'];
				$obj->email = $row['Email'];
				$obj->events[] = $row;
				$users[$prn] = $obj;
			} else {
				$users[$prn]->events[] = $row;
			}
		}
		return $users;
	}

	// --------------------------------------------------------------------
	function _format_events($notification)
	{
		$s = '';
		$s .= '<p>'. $notification->user_name . ' (' . $notification->user . ')' .'</p>';
		$s .=  '<table>';
		$s .= '<tr>';
		$s .= '<th>Event</th><th>Entity</th><th>Link</th>';
		$s .= '<th>Campaign</th><th>Role</th>';
		$s .= '</tr>';
		foreach($notification->events as $row) {
			$s .= '<tr>';
			$s .= '<td>' .$row['Event'] . '</td><td>' . $row['Name'] . '</td><td>' . '<a href="' . site_url() . $row['Link'] . '">' . $row['Entity'] . '</a>' . '</td>';
			$s .= '<td>' . $row['Campaign'] . '</td><td>' .  $row['Role'] . '</td>';
			$s .= "</tr>\n";
		}
		$s .=  '</table>';
		return $s;
	}

	// --------------------------------------------------------------------
	function junk()
	{
		$this->load->helper(array('url'));

		$users = $this->_get_notification_info();
		$ul = array_keys($users);
		sort($ul);
		$items = '';
		foreach($ul as $user) {
			$items .= $this->_format_events($users[$user]);
			$items .= "\n";
		}
		$data['items'] = $items;
		$data['prn'] = '';
		$msg = $this->load->view('email/notification_default', $data, true);
		echo $msg;
	}

	// --------------------------------------------------------------------
	function user($user)
	{
		$this->load->helper(array('url'));

		$users = $this->_get_notification_info();

		if(!array_key_exists($user, $users)) {
			echo 'No messages for ' . $user;
		} else {
			$data['items'] = $this->_format_events($users[$user]);
			$data['prn'] = $user;
			$email = "grkiebel@pnl.gov"; // $users[$user]->email;
			$msg = $this->load->view('email/notification_default', $data, true);
			echo $msg;
		}

	}

	// --------------------------------------------------------------------
	function email_user($user)
	{
		$this->load->helper(array('url'));

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$users = $this->_get_notification_info();

		if(!array_key_exists($user, $users)) {
			echo 'No messages for ' . $user;
		} else {
			$data['items'] = $this->_format_events($users[$user]);
			$data['prn'] = $user;
			$email = "grkiebel@pnl.gov"; // $users[$user]->email;
			$msg = $this->load->view('email/notification_default', $data, true);
			mail($email, "Automatic DMS Event Notification", $msg, $headers);
			echo $msg;
		}
	}

	// --------------------------------------------------------------------
	function email()
	{
//		if($_SERVER['SCRIPT_FILENAME'] != 'not.php') exit;

		$this->load->helper(array('url'));

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: DMS Notification <dms@prismweb.pnl.gov>' . "\r\n";

		$users = $this->_get_notification_info();
		$ul = array_keys($users);
		sort($ul);
		$items = array();
		foreach($ul as $user) {
			log_message('error', 'notification/email:' . $user);
			$data['items'] = $this->_format_events($users[$user]);
			$data['prn'] = $user;
			$email = $users[$user]->email; //"grkiebel@pnl.gov";
			$msg = $this->load->view('email/notification_default', $data, true);
			mail($email, "Automatic DMS Event Notification", $msg, $headers);
			sleep  (2);
			//echo  $users[$user]->email;
		}
	}

}


?>