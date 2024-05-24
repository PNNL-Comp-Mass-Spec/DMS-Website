<?php
namespace App\Controllers;

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
 * The daily e-mails are sent via a cron job that runs email_daily_notification.php
 * which instantiates this controller.
 *
 * To manually send the e-mails, go to the following URL, which will *immediately* send e-mails to all users
 * http://dms2.pnl.gov/notification/email
 *
 * To preview all of the e-mails that would be sent, go to
 * http://dms2.pnl.gov/notification/preview
 *
 * To preview the e-mail that would be sent to a specific user, go to:
 * http://dms2.pnl.gov/notification/email_user/D3L243
 * Note that this will also send the daily notification to proteomics@pnnl.gov
 *
 */
class Notification extends DmsBase {
    function __construct()
    {
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
        $sql .= 'SELECT event, name, link, campaign, person_role, event_type_id, entity_type, username, person, user_id, entity, email ';
        $sql .= 'FROM V_Notification_Message_By_Registered_Users ';
        $sql .= 'WHERE entered > DATEADD(HOUR, - 24, GETDATE()) ';
        $sql .= 'ORDER BY username, entity_type, event_type_id, entity';

        $this->db = \Config\Database::connect();
        $this->updateSearchPath($this->db);

        $result = $this->db->query($sql);
        if(!$result) {
            return $users();
        }

        $email = array();
        $rows = $result->getResultArray();
        foreach($rows as $row) {
            $username = $row['username'];
            if(!array_key_exists($username, $users)) {
                $obj = new \stdClass();
                $obj->user = $username;
                $obj->user_name = $row['person'];
                $obj->email = $row['email'];
                $obj->events[] = $row;
                $users[$username] = $obj;
            } else {
                $users[$username]->events[] = $row;
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
            $s .= '<td>' .$row['event'] . '</td><td>' . $row['name'] . '</td><td>' . '<a href="' . site_url($row['link']) . '">' . $row['entity'] . '</a>' . '</td>';
            $s .= '<td>' . $row['campaign'] . '</td><td>' .  $row['person_role'] . '</td>';
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
        helper(['url']);

        $users = $this->_get_notification_info();
        $ul = array_keys($users);
        sort($ul);
        $items = '';
        foreach($ul as $user) {
            $items .= $this->_format_events($users[$user]);
            $items .= "\n";
        }
        $data['items'] = $items;
        $data['username'] = '';
        echo view('email/notification_default', $data);
    }

    /**
     * Show the notification events for a single user
     * @param type $user
     */
    function user($user)
    {
        helper(['url']);

        $users = $this->_get_notification_info();

        if(!array_key_exists($user, $users)) {
            echo 'No messages for ' . $user . "\n";
        } else {
            $data['items'] = $this->_format_events($users[$user]);
            $data['username'] = $user;
            // Unused: $email = $users[$user]->email;
            echo view('email/notification_default', $data);
        }

    }

    /**
     * Send an e-mail to the user if notification events are available
     * @param type $user
     */
    function email_user($user)
    {
        helper(['url']);

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: DMS Notification <dms@prismweb.pnnl.gov>' . "\r\n";

        $users = $this->_get_notification_info();

        if(!array_key_exists($user, $users)) {
            echo 'No messages for ' . $user . "\n";
        } else {
            $data['items'] = $this->_format_events($users[$user]);
            $data['username'] = $user;
            $email = 'proteomics@pnnl.gov';
            $data['userEmail'] = $users[$user]->email;
            $data['isTest'] = true;
            // Uncomment to send the e-mail to the user
            // $email = $users[$user]->email;
            // $data['isTest'] = false;
            $msg = view('email/notification_default', $data);
            mail($email, "Automatic DMS Event Notification", $msg, $headers);
            echo $msg;
        }
    }

    /**
     * Send notification e-mails to all users with available notifications
     */
    function email()
    {
//      Uncomment the following to abort sending e-mails if not called via the expected script
//      if($_SERVER['SCRIPT_FILENAME'] != 'email_daily_notification.php') exit;

        helper(['url']);

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: DMS Notification <dms@prismweb.pnnl.gov>' . "\r\n";

        $users = $this->_get_notification_info();
        $ul = array_keys($users);
        sort($ul);
        foreach($ul as $user) {
            log_message('info', 'notification/email sent to ' . $user);
            $data['items'] = $this->_format_events($users[$user]);
            $data['username'] = $user;
            $email = $users[$user]->email;
            // Uncomment to override the destination e-mail
            // $email = 'debug.user@pnnl.gov';
            $msg = view('email/notification_default', $data);
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
?>
