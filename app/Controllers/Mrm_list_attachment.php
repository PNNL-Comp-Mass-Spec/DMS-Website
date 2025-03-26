<?php
namespace App\Controllers;

class Mrm_list_attachment extends DmsBase {
    function __construct()
    {
        $this->my_tag = "mrm_list_attachment";
        $this->my_title = "MRM Transition List Attachment";
    }

    // --------------------------------------------------------------------
    function download($id)
    {
        $sql = "SELECT File_Name, Attachment_Name, Contents FROM T_Attachments WHERE ID = $id";
        $db = \Config\Database::connect();
        $this->updateSearchPath($db);

        $result = $db->query($sql);
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
