<?php
namespace App\Models;

use CodeIgniter\Model;

class Dms_statistics extends Model {

    // --------------------------------------------------------------------
    function __construct() {
        //Call the Model constructor
        parent::__construct();
    }

    // --------------------------------------------------------------------
    // This function is used by function stats() in Gen.php
    // See https://dms2.pnl.gov/gen/stats
    function get_stats() {
        $results = array();

        //--------------------------------------------------------------
        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Dataset_Count' AND label = 'All'");
        $allDatasets = $builder->get();
        $results['d_total'] = ($allDatasets) ? $allDatasets->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Dataset_Count' AND label = 'Last 7 days'");
        $datasetsLast7Days = $builder->get();
        $results['ld_total'] = ($datasetsLast7Days) ? $datasetsLast7Days->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Dataset_Count' AND label = 'Last 30 days'");
        $datasetsLast30Days = $builder->get();
        $results['md_total'] = ($datasetsLast30Days) ? $datasetsLast30Days->getRow()->total : 0;

        //--------------------------------------------------------------

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Experiment_Count' AND label = 'All'");
        $allExperiments = $builder->get();
        $results['e_total'] = ($allExperiments) ? $allExperiments->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Experiment_Count' AND label = 'Last 7 days'");
        $experimentsLast7Days = $builder->get();
        $results['le_total'] = ($experimentsLast7Days) ? $experimentsLast7Days->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Experiment_Count' AND label = 'Last 30 days'");
        $experimentsLast30Days = $builder->get();
        $results['me_total'] = ($experimentsLast30Days) ? $experimentsLast30Days->getRow()->total : 0;

        //--------------------------------------------------------------

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Campaign_Count' AND label = 'All'");
        $allCampaigns = $builder->get();
        $results['c_total'] = ($allCampaigns) ? $allCampaigns->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Campaign_Count' AND label = 'Last 7 days'");
        $campaignsLast7Days = $builder->get();
        $results['lc_total'] = ($campaignsLast7Days) ? $campaignsLast7Days->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Campaign_Count' AND label = 'Last 30 days'");
        $campaignsLast30Days = $builder->get();
        $results['mc_total'] = ($campaignsLast30Days) ? $campaignsLast30Days->getRow()->total : 0;

        //--------------------------------------------------------------

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Job_Count' AND label = 'All'");
        $allJobs = $builder->get();
        $results['a_total'] = ($allJobs) ? $allJobs->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Job_Count' AND label = 'New'");
        $newJobs = $builder->get();
        $results['na_total'] = ($newJobs) ? $newJobs->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Job_Count' AND label = 'Last 7 days'");
        $jobsLast7Days = $builder->get();
        $results['la_total'] = ($jobsLast7Days) ? $jobsLast7Days->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Job_Count' AND label = 'Last 30 days'");
        $jobsLast30Days = $builder->get();
        $results['ma_total'] = ($jobsLast30Days) ? $jobsLast30Days->getRow()->total : 0;

        //--------------------------------------------------------------

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'CellCulture_Count' AND label = 'All'");
        $allBiomaterial = $builder->get();
        $results['b_total'] = ($allBiomaterial) ? $allBiomaterial->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'CellCulture_Count' AND label = 'Last 7 days'");
        $biomaterialLast7Days = $builder->get();
        $results['lb_total'] = ($biomaterialLast7Days) ? $biomaterialLast7Days->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'CellCulture_Count' AND label = 'Last 30 days'");
        $biomaterialLast30Days = $builder->get();
        $results['mb_total'] = ($biomaterialLast30Days) ? $biomaterialLast30Days->getRow()->total : 0;

        //-----------------------------------------------------

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Organism_Count' AND label = 'All'");
        $allOrganisms = $builder->get();
        $results['o_total'] = ($allOrganisms) ? $allOrganisms->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Organism_Count' AND label = 'Last 7 days'");
        $organismsLast7Days = $builder->get();
        $results['lo_total'] = ($organismsLast7Days) ? $organismsLast7Days->getRow()->total : 0;

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'Organism_Count' AND label = 'Last 30 days'");
        $organismsLast30Days = $builder->get();
        $results['mo_total'] = ($organismsLast30Days) ? $organismsLast30Days->getRow()->total : 0;

        //-----------------------------------------------------

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'RawDataTB' AND label = 'All'");
        $allRawDataTB = $builder->get();
        $rawDataTBToUse = ($allRawDataTB) ? $allRawDataTB->getRow()->total : 0;
        $results['r_total'] = sprintf("%8.2f", $rawDataTBToUse);

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'RawDataTB' AND label = 'Last 7 days'");
        $rawDataTBLast7Days = $builder->get();
        $rawDataTBToUseLast7Days = ($rawDataTBLast7Days) ? $rawDataTBLast7Days->getRow()->total : 0;
        $results['lr_total'] = sprintf("%8.2f", $rawDataTBToUseLast7Days);

        $builder = $this->db->table("t_general_statistics")->select("value AS total")->where("category = 'RawDataTB' AND label = 'Last 30 days'");
        $rawDataTBLast30Days = $builder->get();
        $rawDataTBToUseLast30Days = ($rawDataTBLast30Days) ? $rawDataTBLast30Days->getRow()->total : 0;
        $results['mr_total'] = sprintf("%8.2f", $rawDataTBToUseLast30Days);

        return $results;
    }
}
?>
