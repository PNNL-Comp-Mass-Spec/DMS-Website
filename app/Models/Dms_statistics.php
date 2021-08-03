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
    function get_stats() {
        $results = array();

        //--------------------------------------------------------------
        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Dataset_Count' AND Label = 'All'");
        $allDatasets = $builder->get();
        $results['d_total'] = ($allDatasets) ? $allDatasets->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Dataset_Count' AND Label = 'Last 7 days'");
        $datasetsLast7Days = $builder->get();
        $results['ld_total'] = ($datasetsLast7Days) ? $datasetsLast7Days->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Dataset_Count' AND Label = 'Last 30 days'");
        $datasetsLast30Days = $builder->get();
        $results['md_total'] = ($datasetsLast30Days) ? $datasetsLast30Days->row()->total : 0;

        //--------------------------------------------------------------

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Experiment_Count' AND Label = 'All'");
        $allExperiments = $builder->get();
        $results['e_total'] = ($allExperiments) ? $allExperiments->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Experiment_Count' AND Label = 'Last 7 days'");
        $experimentsLast7Days = $builder->get();
        $results['le_total'] = ($experimentsLast7Days) ? $experimentsLast7Days->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Experiment_Count' AND Label = 'Last 30 days'");
        $experimentsLast30Days = $builder->get();
        $results['me_total'] = ($experimentsLast30Days) ? $experimentsLast30Days->row()->total : 0;

        //--------------------------------------------------------------

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Campaign_Count' AND Label = 'All'");
        $allCampaigns = $builder->get();
        $results['c_total'] = ($allCampaigns) ? $allCampaigns->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Campaign_Count' AND Label = 'Last 7 days'");
        $campaignsLast7Days = $builder->get();
        $results['lc_total'] = ($campaignsLast7Days) ? $campaignsLast7Days->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Campaign_Count' AND Label = 'Last 30 days'");
        $campaignsLast30Days = $builder->get();
        $results['mc_total'] = ($campaignsLast30Days) ? $campaignsLast30Days->row()->total : 0;

        //--------------------------------------------------------------

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Job_Count' AND Label = 'All'");
        $allJobs = $builder->get();
        $results['a_total'] = ($allJobs) ? $allJobs->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Job_Count' AND Label = 'New'");
        $newJobs = $builder->get();
        $results['na_total'] = ($newJobs) ? $newJobs->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Job_Count' AND Label = 'Last 7 days'");
        $jobsLast7Days = $builder->get();
        $results['la_total'] = ($jobsLast7Days) ? $jobsLast7Days->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Job_Count' AND Label = 'Last 30 days'");
        $jobsLast30Days = $builder->get();
        $results['ma_total'] = ($jobsLast30Days) ? $jobsLast30Days->row()->total : 0;

        //--------------------------------------------------------------

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'CellCulture_Count' AND Label = 'All'");
        $allBiomaterial = $builder->get();
        $results['b_total'] = ($allBiomaterial) ? $allBiomaterial->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'CellCulture_Count' AND Label = 'Last 7 days'");
        $biomaterialLast7Days = $builder->get();
        $results['lb_total'] = ($biomaterialLast7Days) ? $biomaterialLast7Days->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'CellCulture_Count' AND Label = 'Last 30 days'");
        $biomaterialLast30Days = $builder->get();
        $results['mb_total'] = ($biomaterialLast30Days) ? $biomaterialLast30Days->row()->total : 0;

        //-----------------------------------------------------

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Organism_Count' AND Label = 'All'");
        $allOrganisms = $builder->get();
        $results['o_total'] = ($allOrganisms) ? $allOrganisms->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Organism_Count' AND Label = 'Last 7 days'");
        $organismsLast7Days = $builder->get();
        $results['lo_total'] = ($organismsLast7Days) ? $organismsLast7Days->row()->total : 0;

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'Organism_Count' AND Label = 'Last 30 days'");
        $organismsLast30Days = $builder->get();
        $results['mo_total'] = ($organismsLast30Days) ? $organismsLast30Days->row()->total : 0;

        //-----------------------------------------------------

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'RawDataTB' AND Label = 'All'");
        $allRawDataTB = $builder->get();
        $rawDataTBToUse = ($allRawDataTB) ? $allRawDataTB->row()->total : 0;
        $results['r_total'] = sprintf("%8.2f", $rawDataTBToUse);

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'RawDataTB' AND Label = 'Last 7 days'");
        $rawDataTBLast7Days = $builder->get();
        $rawDataTBToUseLast7Days = ($rawDataTBLast7Days) ? $rawDataTBLast7Days->row()->total : 0;
        $results['lr_total'] = sprintf("%8.2f", $rawDataTBToUseLast7Days);

        $builder = $this->db->table("T_General_Statistics")->select("Value AS total")->where("Category = 'RawDataTB' AND Label = 'Last 30 days'");
        $rawDataTBLast30Days = $builder->get();
        $rawDataTBToUseLast30Days = ($rawDataTBLast30Days) ? $rawDataTBLast30Days->row()->total : 0;
        $results['mr_total'] = sprintf("%8.2f", $rawDataTBToUseLast30Days);

        return $results;
    }
}
?>
