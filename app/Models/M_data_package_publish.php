<?php
namespace App\Models;

use CodeIgniter\Model;

class M_data_package_publish extends Model {

    /**
     * Constructor
     */
    function __construct() {
        //Call the Model constructor
        parent :: Model();
    }

    /**
     * Get paths to analysis job results folders for jobs in given data package
     * @param type $data_package_ID
     * @return type
     */
    function get_data_package_job_results_folder_paths($data_package_ID) {
        $sql = <<<EOD
SELECT
        TDPA.Job ,
        ISNULL(AP.AP_archive_path, '') + '/' +
        ISNULL(TDS.DS_folder_name, TDS.Dataset_Num) + '/' +
        ISNULL(AJ.AJ_resultsFolderName, '') AS Folder_Path
FROM    S_V_Data_Package_Analysis_Jobs_Export AS TDPA
        INNER JOIN T_Dataset AS TDS ON TDS.Dataset_Num = TDPA.Dataset
        INNER JOIN T_Dataset_Archive AS DA ON DA.AS_Dataset_ID = TDS.Dataset_ID
        INNER JOIN T_Archive_Path AS AP ON AP.AP_path_ID = DA.AS_storage_path_ID
        INNER JOIN T_Analysis_Job AS AJ ON AJ.AJ_jobID = TDPA.Job
WHERE TDPA.Data_Package_ID = $data_package_ID
EOD;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * Get paths to dataset folders for datasets in given data package
     * @param type $data_package_ID
     * @return type
     */
    function get_data_package_dataset_folder_paths($data_package_ID) {
        $sql = <<<EOD
SELECT  DS.Dataset_ID ,
        ISNULL(AP.AP_archive_path, '') + '/' +
        ISNULL(DS.DS_folder_name, DS.Dataset_Num) AS Folder_Path
FROM    S_V_Data_Package_Datasets_Export AS TDPA
        INNER JOIN T_Dataset AS DS ON DS.Dataset_ID = TDPA.Dataset_ID
        INNER JOIN T_Dataset_Archive AS DA ON DA.AS_Dataset_ID = DS.Dataset_ID
        INNER JOIN T_Archive_Path AS AP ON AP.AP_path_ID = DA.AS_storage_path_ID
WHERE   TDPA.Data_Package_ID = $data_package_ID
EOD;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * Get paths to share folder for given data package
     * @param type $data_package_ID
     * @return type
     */
    function get_data_package_share_folder_paths($data_package_ID) {
        $sql = <<<EOD
SELECT REPLACE(Storage_Path_Relative, '\', '/') AS Storage_Path
FROM    S_V_Data_Package_Export AS data_package_path
WHERE   ID = $data_package_ID
EOD;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * Get data package metadata for given data package
     * @param type $data_package_ID
     * @return type
     */
    function get_data_package_metadata($data_package_ID) {
        $sql = <<<EOD
SELECT
    ID ,
    Name ,
    Description ,
    Owner ,
    Team ,
    State ,
    [Package Type] AS PackageType,
    Requester ,
    Total ,
    Jobs ,
    Datasets ,
    Experiments ,
    Biomaterial ,
    CONVERT(VARCHAR(24), Created, 101) AS Created
FROM    S_V_Data_Package_Export AS package
WHERE   ID = $data_package_ID
EOD;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * Get experiment metadata for given data package
     * @param type $data_package_ID
     * @return type
     */
    function get_data_package_experiment_metadata($data_package_ID) {
        $sql = <<<EOD
SELECT
    Experiment_ID ,
    Experiment ,
    TRG.OG_name AS Organism,
    TC.Campaign_Num AS Campaign,
    CONVERT(VARCHAR(24), Created, 101) AS Created ,
    TEX.EX_reason AS Reason,
    [Package Comment] AS Package_Comment
FROM     S_V_Data_Package_Experiments_Export AS TDPA
    INNER JOIN T_Experiments TEX ON TDPA.Experiment_ID = TEX.Exp_ID
    INNER JOIN T_Campaign TC ON TC.Campaign_ID = TEX.EX_campaign_ID
    INNER JOIN dbo.T_Organisms TRG ON TRG.Organism_ID = TEX.EX_organism_ID
WHERE   TDPA.Data_Package_ID = $data_package_ID
EOD;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * Get dataset metadata for given data package
     * @param type $data_package_ID
     * @return type
     */
    function get_data_package_dataset_metadata($data_package_ID) {
        $sql = <<<EOD
SELECT
    DS.Dataset_ID ,
    Dataset ,
    -- Experiment ,
    DS.Exp_ID as Experiment_ID,
    Instrument ,
    CONVERT(VARCHAR(24), Created, 101) AS Created ,
    [Package Comment] AS Package_Comment
FROM    S_V_Data_Package_Datasets_Export AS TDPA
    INNER JOIN T_Dataset AS DS ON DS.Dataset_ID = TDPA.Dataset_ID
WHERE   TDPA.Data_Package_ID = $data_package_ID
EOD;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * Get job metadata for given data package
     * @param type $data_package_ID
     * @return type
     */
    function get_data_package_job_metadata($data_package_ID) {
        $sql = <<<EOD
SELECT
    VMA.Job ,
    VMA.Dataset_ID ,
    VMA.Tool ,
    VMA.Parameter_File ,
    VMA.Settings_File ,
    VMA.[Protein Collection List] AS Protein_Collection_List,
    VMA.[Protein Options] as Protein_Options,
    VMA.Comment ,
    VMA.State ,
    TPA.[Package Comment] AS Package_Comment
FROM  S_V_Data_Package_Analysis_Jobs_Export AS TPA
    INNER JOIN V_Mage_Analysis_Jobs AS VMA  ON VMA.Job = TPA.Job
WHERE TPA.Data_Package_ID = $data_package_ID
EOD;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
?>
