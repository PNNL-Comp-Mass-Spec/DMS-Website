<?php  
	if (!defined('BASEPATH')) {
		exit('No direct script access allowed');
	}

/**
 * Create HTML to display overall DMS statistics
 * @param type $results
 * @return string
 */
function make_stats_display($results)
{
	$str = '';
	$str .= "<table class='LRep' style='font-size: 1.1em; '>";
	$str .= "<tr >";
	$str .= "<th> &nbsp; </th>";
	$str .= "<th><a href='".site_url()."dataset/report'>Datasets</a></th>";
	$str .= "<th><a href='".site_url()."experiment/report'>Experiments</a></th>";
	$str .= "<th><a href='".site_url()."cell_culture/report'>Biomaterial</a></th>";
	$str .= "<th><a href='".site_url()."campaign/report'>Campaigns</a></th>";
	$str .= "<th><a href='".site_url()."analysis_job/report'>Analyses</a></th>";
	$str .= "<th><a href='".site_url()."organism/report'>Organisms</a></th>";
	$str .= "<th>Raw Data (TB)</th>";
	$str .= "</tr>";
	$str .= "<tr class='ReportOddRow'>";
	$str .= "<td class='context' >Total:</td>";
	$str .= "<td class='rht'>".$results['d_total']."</td>";
	$str .= "<td class='rht'>".$results['e_total']."</td>";
	$str .= "<td class='rht'>".$results['b_total']."</td>";
	$str .= "<td class='rht'>".$results['c_total']."</td>";
	$str .= "<td class='rht'>"."[".$results['na_total']."] ".$results['a_total']."</td>";
	$str .= "<td class='rht'>".$results['o_total']."</td>";
	$str .= "<td class='rht'>".$results['r_total']."</td>";
	$str .= "</tr>";
	$str .= "<tr class='ReportEvenRow'>";
	$str .= "<td class='context' >Last 7 days:</td>";
	$str .= "<td class='rht'>".$results['ld_total']."</td>";
	$str .= "<td class='rht'>".$results['le_total']."</td>";
	$str .= "<td class='rht'>".$results['lb_total']."</td>";
	$str .= "<td class='rht'>".$results['lc_total']."</td>";
	$str .= "<td class='rht'>".$results['la_total']."</td>";
	$str .= "<td class='rht'>".$results['lo_total']."</td>";
	$str .= "<td class='rht'>".$results['lr_total']."</td>";
	$str .= "</tr>";
	$str .= "<tr class='ReportOddRow'>";
	$str .= "<td class='context' >Last 30 days:</td>";
	$str .= "<td class='rht'>".$results['md_total']."</td>";
	$str .= "<td class='rht'>".$results['me_total']."</td>";
	$str .= "<td class='rht'>".$results['mb_total']."</td>";
	$str .= "<td class='rht'>".$results['mc_total']."</td>";
	$str .= "<td class='rht'>".$results['ma_total']."</td>";
	$str .= "<td class='rht'>".$results['mo_total']."</td>";
	$str .= "<td class='rht'>".$results['mr_total']."</td>";
	$str .= "</tr>";
	$str .= "</table>";
	$str .= "";
	$str .= "</table>\n";

	// $str .= "<br>" . Base_controller::var_dump_ex($results);
	return $str;
}
