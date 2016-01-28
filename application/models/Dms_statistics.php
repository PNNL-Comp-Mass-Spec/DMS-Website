<?php		class Dms_statistics extends CI_Model {
	
	// --------------------------------------------------------------------
	function __construct() 
	{
		//Call the Model constructor
		parent::__construct();
	}
	
	// --------------------------------------------------------------------
	function get_stats()
	{
		$results = array();
		
		//--------------------------------------------------------------
		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Dataset_Count' AND Label = 'All'");
		$query = $this->db->get();
		$results['d_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Dataset_Count' AND Label = 'Last 7 days'");
		$query = $this->db->get();
		$results['ld_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Dataset_Count' AND Label = 'Last 30 days'");
		$query = $this->db->get();
		$results['md_total'] = ($query)?$query->row()->total:0;

		//--------------------------------------------------------------

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Experiment_Count' AND Label = 'All'");
		$query = $this->db->get();
		$results['e_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Experiment_Count' AND Label = 'Last 7 days'");
		$query = $this->db->get();
		$results['le_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Experiment_Count' AND Label = 'Last 30 days'");
		$query = $this->db->get();
		$results['me_total'] = ($query)?$query->row()->total:0;

		//--------------------------------------------------------------

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Campaign_Count' AND Label = 'All'");
		$query = $this->db->get();
		$results['c_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Campaign_Count' AND Label = 'Last 7 days'");
		$query = $this->db->get();
		$results['lc_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Campaign_Count' AND Label = 'Last 30 days'");
		$query = $this->db->get();
		$results['mc_total'] = ($query)?$query->row()->total:0;

		//--------------------------------------------------------------

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Job_Count' AND Label = 'All'");
		$query = $this->db->get();
		$results['a_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Job_Count' AND Label = 'New'");
		$query = $this->db->get();
		$results['na_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Job_Count' AND Label = 'Last 7 days'");
		$query = $this->db->get();
		$results['la_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Job_Count' AND Label = 'Last 30 days'");
		$query = $this->db->get();
		$results['ma_total'] = ($query)?$query->row()->total:0;

		//--------------------------------------------------------------

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'CellCulture_Count' AND Label = 'All'");
		$query = $this->db->get();
		$results['b_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'CellCulture_Count' AND Label = 'Last 7 days'");
		$query = $this->db->get();
		$results['lb_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'CellCulture_Count' AND Label = 'Last 30 days'");
		$query = $this->db->get();
		$results['mb_total'] = ($query)?$query->row()->total:0;

		//-----------------------------------------------------

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Organism_Count' AND Label = 'All'");
		$query = $this->db->get();
		$results['o_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Organism_Count' AND Label = 'Last 7 days'");
		$query = $this->db->get();
		$results['lo_total'] = ($query)?$query->row()->total:0;

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'Organism_Count' AND Label = 'Last 30 days'");
		$query = $this->db->get();
		$results['mo_total'] = ($query)?$query->row()->total:0;

 		//-----------------------------------------------------

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'RawDataTB' AND Label = 'All'");
		$query = $this->db->get();
		$n = ($query)?$query->row()->total:0;
		$results['r_total'] = sprintf("%8.2f", $n);

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'RawDataTB' AND Label = 'Last 7 days'");
		$query = $this->db->get();
		$n = ($query)?$query->row()->total:0;
		$results['lr_total'] = sprintf("%8.2f", $n);

		$this->db->select("Value AS total")->from("T_General_Statistics")->where("Category = 'RawDataTB' AND Label = 'Last 30 days'");
		$query = $this->db->get();
		$n = ($query)?$query->row()->total:0;
		$results['mr_total'] = sprintf("%8.2f", $n);

		return $results;
	}
	

}
?>
