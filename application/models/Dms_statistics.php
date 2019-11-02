<?php

class Dms_statistics extends CI_Model {

    // --------------------------------------------------------------------
    function __construct() {
        //Call the Model constructor
        parent::__construct();
    }

    // --------------------------------------------------------------------
    function get_stats() {
        $results = array();

        //--------------------------------------------------------------
        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Dataset_Count' AND Label = 'All'");
        $allDatasets = $this->db->get();
        $results['d_total'] = ($allDatasets) ? $allDatasets->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Dataset_Count' AND Label = 'Last 7 days'");
        $datasetsLast7Days = $this->db->get();
        $results['ld_total'] = ($datasetsLast7Days) ? $datasetsLast7Days->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Dataset_Count' AND Label = 'Last 30 days'");
        $datasetsLast30Days = $this->db->get();
        $results['md_total'] = ($datasetsLast30Days) ? $datasetsLast30Days->row()->total : 0;

        //--------------------------------------------------------------

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Experiment_Count' AND Label = 'All'");
        $allExperiments = $this->db->get();
        $results['e_total'] = ($allExperiments) ? $allExperiments->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Experiment_Count' AND Label = 'Last 7 days'");
        $experimentsLast7Days = $this->db->get();
        $results['le_total'] = ($experimentsLast7Days) ? $experimentsLast7Days->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Experiment_Count' AND Label = 'Last 30 days'");
        $experimentsLast30Days = $this->db->get();
        $results['me_total'] = ($experimentsLast30Days) ? $experimentsLast30Days->row()->total : 0;

        //--------------------------------------------------------------

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Campaign_Count' AND Label = 'All'");
        $allCampaigns = $this->db->get();
        $results['c_total'] = ($allCampaigns) ? $allCampaigns->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Campaign_Count' AND Label = 'Last 7 days'");
        $campaignsLast7Days = $this->db->get();
        $results['lc_total'] = ($campaignsLast7Days) ? $campaignsLast7Days->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Campaign_Count' AND Label = 'Last 30 days'");
        $campaignsLast30Days = $this->db->get();
        $results['mc_total'] = ($campaignsLast30Days) ? $campaignsLast30Days->row()->total : 0;

        //--------------------------------------------------------------

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Job_Count' AND Label = 'All'");
        $allJobs = $this->db->get();
        $results['a_total'] = ($allJobs) ? $allJobs->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Job_Count' AND Label = 'New'");
        $newJobs = $this->db->get();
        $results['na_total'] = ($newJobs) ? $newJobs->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Job_Count' AND Label = 'Last 7 days'");
        $jobsLast7Days = $this->db->get();
        $results['la_total'] = ($jobsLast7Days) ? $jobsLast7Days->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Job_Count' AND Label = 'Last 30 days'");
        $jobsLast30Days = $this->db->get();
        $results['ma_total'] = ($jobsLast30Days) ? $jobsLast30Days->row()->total : 0;

        //--------------------------------------------------------------

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'CellCulture_Count' AND Label = 'All'");
        $allBiomaterial = $this->db->get();
        $results['b_total'] = ($allBiomaterial) ? $allBiomaterial->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'CellCulture_Count' AND Label = 'Last 7 days'");
        $biomaterialLast7Days = $this->db->get();
        $results['lb_total'] = ($biomaterialLast7Days) ? $biomaterialLast7Days->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'CellCulture_Count' AND Label = 'Last 30 days'");
        $biomaterialLast30Days = $this->db->get();
        $results['mb_total'] = ($biomaterialLast30Days) ? $biomaterialLast30Days->row()->total : 0;

        //-----------------------------------------------------

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Organism_Count' AND Label = 'All'");
        $allOrganisms = $this->db->get();
        $results['o_total'] = ($allOrganisms) ? $allOrganisms->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Organism_Count' AND Label = 'Last 7 days'");
        $organismsLast7Days = $this->db->get();
        $results['lo_total'] = ($organismsLast7Days) ? $organismsLast7Days->row()->total : 0;

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Organism_Count' AND Label = 'Last 30 days'");
        $organismsLast30Days = $this->db->get();
        $results['mo_total'] = ($organismsLast30Days) ? $organismsLast30Days->row()->total : 0;

        //-----------------------------------------------------

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'RawDataTB' AND Label = 'All'");
        $allRawDataTB = $this->db->get();
        $rawDataTBToUse = ($allRawDataTB) ? $allRawDataTB->row()->total : 0;
        $results['r_total'] = sprintf("%8.2f", $rawDataTBToUse);

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'RawDataTB' AND Label = 'Last 7 days'");
        $rawDataTBLast7Days = $this->db->get();
        $rawDataTBToUseLast7Days = ($rawDataTBLast7Days) ? $rawDataTBLast7Days->row()->total : 0;
        $results['lr_total'] = sprintf("%8.2f", $rawDataTBToUseLast7Days);

        $this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'RawDataTB' AND Label = 'Last 30 days'");
        $rawDataTBLast30Days = $this->db->get();
        $rawDataTBToUseLast30Days = ($rawDataTBLast30Days) ? $rawDataTBLast30Days->row()->total : 0;
        $results['mr_total'] = sprintf("%8.2f", $rawDataTBToUseLast30Days);

        return $results;
    }

}
