<?php
namespace App\Controllers;

class Mrm_list_attachment extends DmsBase {
    function __construct()
    {
        $this->my_tag = "mrm_list_attachment";
        $this->my_title = "MRM Transition List Attachment";
        $this->my_create_action = "enter";
        $this->my_edit_action = "enter";
        $this->my_list_action = "unrestricted";
        $this->my_export_action = "unrestricted";
    }

    // --------------------------------------------------------------------
    function download($id)
    {
        $sql = "SELECT File_Name, Attachment_Name, Contents FROM T_Attachments WHERE ID = $id";
        $this->db = \Config\Database::connect();
        $this->updateSearchPath($this->db);

        $result = $this->db->query($sql);
        //
        if(!$result) {
            $currentTimestamp = date("Y-m-d");
            echo "No results found for attachment ID $id; see writable/logs/log-$currentTimestamp.php";
            return;
        }
        $file_info = $result->getRow();
        $filename = str_replace(" ", "_", $file_info->Attachment_Name) . ".txt";

        $this->response->setContentType("text/plain");
        $this->response->setHeader("Content-Disposition", "attachment; filename=$filename");
        echo $file_info->Contents;
    }
}
?>
