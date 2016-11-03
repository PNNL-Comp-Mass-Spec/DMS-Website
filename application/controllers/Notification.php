<?php
require("Base_controller.php");

/**
 * This controller allows users to sign up to be notified of
 * DMS events of the last 24 hours that are associated with 
 * one or more campaign research teams that a user is a member of
 *
 * Example URLs:
 * http://dms2.pnl.gov/notification/report
 * http://dms2.pnl.gov/notification/user/D3L243
 * http://dms2.pnl.gov/notification/edit/D3L243
 * 
 * http://dms2.pnl.gov/notification/preview
 * http://dms2.pnl.gov/notification/email_user/D3L243
 * 
 * The daily e-mails are sent via a cron job that runs email_daily_notification.php
 * which instantiates this controller. To manually send the e-mails, go to:
 * http://dms2.pnl.gov/notification/email
 */
class Notification extends Base_controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();

		$this->my_tag = "notification";
		$this->my_title = "Notification";
	}

	/**
	 * Retrieve notification events from the database
	 * @return \stdClass
	 */
	function _get_notification_info()
	{
		$users = array();
		// Query the database
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

	/**
	 * Format the events as a table
	 * @param type $notification
	 * @return string
	 */
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

	/**
	 * Preview all of the notification events
	 */
	function preview()
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

	/**
	 * Show the notification events for a single user
	 * @param type $user
	 */
	function user($user)
	{
		$this->load->helper(array('url'));

		$users = $this->_get_notification_info();

		if(!array_key_exists($user, $users)) {
			echo 'No messages for ' . $user . "\n";
		} else {
			$data['items'] = $this->_format_events($users[$user]);
			$data['prn'] = $user;
			// Unused: $email = $users[$user]->email;
			$msg = $this->load->view('email/notification_default', $data, true);
			echo $msg;
		}

	}

	/**
	 * Send an e-mail to the user if notification events are available
	 * @param type $user
	 */
	function email_user($user)
	{
		$this->load->helper(array('url'));

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: DMS Notification <dms@prismweb.pnnl.gov>' . "\r\n";
		
		$users = $this->_get_notification_info();

		if(!array_key_exists($user, $users)) {
			echo 'No messages for ' . $user . "\n";
		} else {
			$data['items'] = $this->_format_events($users[$user]);
			$data['prn'] = $user;
			$email = 'proteomics@pnnl.gov';
			// Uncomment to send the e-mail to the user
			// $email = $users[$user]->email;
			$msg = $this->load->view('email/notification_default', $data, true);
			mail($email, "Automatic DMS Event Notification", $msg, $headers);
			echo $msg;
		}
	}

	/**
	 * Send notification e-mails to all users with available notifications
	 */
	function email()
	{
//		Uncomment the following to abort sending e-mails if not called via the expected script
//		if($_SERVER['SCRIPT_FILENAME'] != 'email_daily_notification.php') exit;

		$this->load->helper(array('url'));

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: DMS Notification <dms@prismweb.pnnl.gov>' . "\r\n";

		$users = $this->_get_notification_info();
		$ul = array_keys($users);
		sort($ul);
		foreach($ul as $user) {
			// Pass true to log_message to force the message to be logged even if the log threshold is Error only
			log_message('info', 'notification/email sent to ' . $user, true);
			$data['items'] = $this->_format_events($users[$user]);
			$data['prn'] = $user;
			$email = $users[$user]->email;
			// Uncomment to override the destination e-mail
			// $email = 'debug.user@pnnl.gov';
			$msg = $this->load->view('email/notification_default', $data, true);
			mail($email, "Automatic DMS Event Notification", $msg, $headers);
			sleep (1);
			
			if ($email === $users[$user]->email) {
				echo "Mail for $user sent to $email\n";
			} else {
				echo "Mail for $user sent to $email (would typically go to " . $users[$user]->email . ')\n';
			}
		}
	}

}
