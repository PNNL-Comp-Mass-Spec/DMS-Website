<?php
namespace App\Controllers;

class Ers extends BaseController {

    var $my_tag = "ers";

    // --------------------------------------------------------------------
    function index()
    {
    }

    // --------------------------------------------------------------------
    function proposals()
    {
        $this->table = new \CodeIgniter\View\Table();
        $ersDB = \Config\Database::connect('ers');

        // get list of proposals from ers
        // PROPOSAL_ID  TITLE   DESCRIPTION
        $sql = 'select PROPOSAL_ID, TITLE, DESCRIPTION from VW_ALL_ACTIVE_PROPOSALS';
        $result = $ersDB->query($sql);
        //
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "No results found for active EUS proposals; see writable/logs/log-$currentTimestamp.php";
            return;
        }
        echo $this->table->generate($result);
    }

    // --------------------------------------------------------------------
    function users()
    {
        $this->table = new \CodeIgniter\View\Table();
        $ersDB = \Config\Database::connect('ers');

        // get list of users for proposals from ers PROPOSAL_ID, HANFORD_ID
        // PROPOSAL_ID  TITLE   DESCRIPTION HANFORD_ID
        $sql = 'select * from VW_USERS_ACTIVE_PROPOSALS';
        $result = $ersDB->query($sql);
        //
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "No results found for active EUS users; see writable/logs/log-$currentTimestamp.php";
            return;
        }
        echo $this->table->generate($result);
    }

    // --------------------------------------------------------------------
    function dms_proposals()
    {
        $this->table = new \CodeIgniter\View\Table();
        $dmsDB = \Config\Database::connect('default');

        // get list of proposals from ers
        $sql = 'SELECT PROPOSAL_ID, TITLE FROM T_EUS_Proposals';
        $result = $dmsDB->query($sql);
        //
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "No results found for EUS proposals; see writable/logs/log-$currentTimestamp.php";
            return;
        }
        echo $this->table->generate($result);
    }

    // --------------------------------------------------------------------
    function dms_users()
    {
        $this->table = new \CodeIgniter\View\Table();
        $dmsDB = \Config\Database::connect('default');

        // Get list of proposals from EUS (Nexus)
        $sql = 'SELECT User_ID, User_Name FROM V_EUS_Users_ID';
        $result = $dmsDB->query($sql);
        //
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "No results found for EUS users; see writable/logs/log-$currentTimestamp.php";
            return;
        }
        echo $this->table->generate($result);
    }

    // --------------------------------------------------------------------
    function new_proposals()
    {
        $this->table = new \CodeIgniter\View\Table();

        // get list of proposals from ers
        $dmsDB = \Config\Database::connect('default');
        $sql = 'SELECT PROPOSAL_ID, TITLE FROM T_EUS_Proposals';
        $result = $dmsDB->query($sql);
        //
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "No results found for EUS proposals; see writable/logs/log-$currentTimestamp.php";
            return;
        }
        $dms_proposals = array();
        foreach($result->getResult() as $row) {
            $dms_proposals[$row->PROPOSAL_ID] = $row->TITLE;
        }

        // get list of proposals from ers
        $ersDB = \Config\Database::connect('ers');
        $sql = 'select PROPOSAL_ID, TITLE, DESCRIPTION from VW_ALL_ACTIVE_PROPOSALS ORDER BY PROPOSAL_ID DESC';
        $result = $ersDB->query($sql);
        //
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "No ERS results found; see writable/logs/log-$currentTimestamp.php";
            return;
        }
        $ers_proposals = array();
        foreach($result->getResult() as $row) {
            $ers_proposals[$row->PROPOSAL_ID] = $row->TITLE;
        }
//      echo $this->table->generate($ers_proposals);
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
?>
