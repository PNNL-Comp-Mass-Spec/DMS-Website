<?php
namespace App\Controllers;

class Data_package extends DmsBase {

    function __construct()
    {
        $this->my_tag = "data_package";
        $this->my_title = "DMS Data Package";
        $this->my_create_action = "enter";
        $this->my_edit_action = "enter";
        $this->my_operation_action = "operation";
        $this->my_list_action = "unrestricted";
        $this->my_export_action = "unrestricted";
        $this->my_show_action = "unrestricted";

        // This causes a link to the detail report page to appear on entry page following successful submit
//      $this->my_post_submission_detail_id = "ID";
    }

    /**
     * Get information about jobs associated with the given tool for the given data package (likely not in use)
     * Example URL: https://dms2.pnl.gov/data_package/ag/3142/MSGFPlus_MzML/PackageJobCount
     * @param type $id Data Package ID
     * @param type $tool Analysis tool name
     * @param type $mode Query mode: NoPackageJobs, NoDMSJobs, or PackageJobCount
     * @return string
     */
    function ag($id, $tool, $mode) {
        helper(['url', 'text']);

        $this->db = \Config\Database::connect('package');
        $this->updateSearchPath($this->db);

        // TODO: postgresfix!
        $sql = "check_data_package_dataset_job_coverage($id, '$tool', '$mode')";
        $builder = $this->db->table($sql);
        $resultSet = $builder->get();
        if(!$resultSet) {
            $currentTimestamp = date("Y-m-d");
            return "Error querying database via check_data_package_dataset_job_coverage; see writable/logs/log-$currentTimestamp.php";
        }
        if ($resultSet->getNumRows() == 0) {
            return "No rows found calling check_data_package_dataset_job_coverage";
        }
        $result = $resultSet->getResultArray();
        $fields = $resultSet->list_fields();

        header("Content-type: text/plain");
        echo "-- $mode Package:$id Tool:$tool --\n";
        foreach($result as $row) {
            echo $row['Dataset']."\n";
        }
    }

    // --------------------------------------------------------------------
    function metadata($id) {
        helper(['url', 'text']);

        $this->db = \Config\Database::connect('package');
        $this->updateSearchPath($this->db);

        $sqlList = array(
            "EMSL_Proposals" => "SELECT DISTINCT Proposal FROM V_Data_Package_Datasets_List_Report WHERE NOT Proposal IS NULL AND ID = $id",
            "Package" => "SELECT * FROM V_Data_Package_Detail_Report WHERE ID = $id",
            "Jobs" => "SELECT * FROM V_Data_Package_Analysis_Jobs_List_Report WHERE ID = $id",
            "Datasets" => "SELECT * FROM V_Data_Package_Datasets_List_Report WHERE ID = $id",
            "Experiments" => "SELECT * FROM V_Data_Package_Experiments_List_Report WHERE ID = $id",
            "Biomaterial" => "SELECT * FROM V_Data_Package_Biomaterial_List_Report WHERE ID = $id",
        );

        $ignoreColumns = array('ID', 'Dataset Folder Path', 'Archive Folder Path', 'Share Path', 'Web Path', 'PRISM Wiki');

        header("Content-type: text/plain");

        echo "<?xml version='1.0' encoding='utf-8'?>\n";
        echo "<metadata>\n";
        echo "<DMS ID='$id' />\n";
        foreach($sqlList as $section => $sql) {
            echo "<$section>\n";
            $resultSet = $this->db->query($sql);
            if(!$resultSet) continue;
            if ($resultSet->getNumRows() == 0) continue;
            $result = $resultSet->getResultArray();
            $cols = array_keys(current($result));
            foreach($result as $row) {
                echo "<item ";
                foreach($cols as $name) {
                    if(in_array($name,$ignoreColumns)) continue;
                    $value = ($row[$name])?$row[$name]:"";
                    $name = str_replace(" ","_", $name);
                    $value = htmlspecialchars($value);
                    echo "$name='$value' ";
                }
                echo " />\n";
            }
            echo "</$section>\n";
        }
        echo "</metadata>\n";
    }
}
?>
