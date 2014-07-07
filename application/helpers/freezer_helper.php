<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


// --------------------------------------------------------------------
// render contents of location
function render_location_contents($location, $contents)
{
	$loc = $location['Location'];
	$avail = $location['Available'];
	
	// render location
	$s = '';
	$s .= "<div>".$loc."</div>";
	
	// if space for more containers is available
	// render link to make a new one
	if( ($avail != '0')) {
			$s .= "<div>";
			$s .= "<a href='".site_url()."material_container/create/init/-/-/$loc'>Add New Container</a>";								
			$s .= "</div>";		
	}

	// render containers, if any
	if(array_key_exists($loc, $contents)) {
		foreach($contents[$loc] as $content) {
			$cn = $content['Container'];
			$s .= "<div>";
			$s .= "<a href='".site_url()."material_container/show/$cn'>".$cn."</a>";								
			$s .= " &nbsp; ";
			$s .= "<span>".$content['Comment']."</span>";								
			$s .= "</div>";
		}
	}
	return $s;
}
// --------------------------------------------------------------------
// build nested array representation of freezer locations
function make_freezer_matrix_array($locs)
{
	$fzr = array();
	foreach($locs as $loc) {
		$status = $loc['Status'];
		$active_desc = "<span style='color:green;'>$status</span>";
		$inactive_desc = "";
		$desc = ($status == 'Active')?$active_desc:$inactive_desc;
		$fzr[$loc['Shelf']][$loc['Rack']][$loc['Row']][$loc['Col']] = $desc;
	}
	return $fzr;
}

// --------------------------------------------------------------------
function make_matrix_row_col_tables($fzr, $table_setup, $tstyl)
{
	// make inner tables (row, col)
	$otr = array();
	// make row for each shelf
	for($shelf = 1; $shelf <= count($fzr); $shelf++) {
		for($rack = 1; $rack <= count($fzr[$shelf]); $rack++) {
			$tbrc = "<table $table_setup $tstyl >\n";
			for($row = 1; $row <= count($fzr[$shelf][$rack]); $row++) {
				$cols = $fzr[$shelf][$rack][$row];
				// make header row
				if($row == 1) {
					$hdr = array_keys($cols);
					$thdr = "<thead><tr>";
					$thdr .= "<th></th>";
					for($i = 0; $i < count($hdr); $i++) {
						$thdr .= "<th style='width:10em;'>Col $hdr[$i]</th>";
					}
					$thdr .= "</tr></thead>\n";
					$tbrc .= $thdr;
				}
				// make rack row
				$tbrc .= "<tr>";
				$tbrc .= "<th>Row $row</th>";
				for($j = 1; $j <= count($cols); $j++) {
					$tbrc .= "<td>";
					$tbrc .= $cols[$j];
					$tbrc .= "</td>";
				}
				$tbrc .= "</tr>";
			}
			$tbrc .= "</table>";		
			$otr[$shelf][$rack] = $tbrc;
		}
	}
	return $otr;
}

// --------------------------------------------------------------------
function render_matrix_table($otr, $table_setup)
{
	// make outer table (shelf, rack) containing inner tables (row, col)
	$tbs = "<table $table_setup >\n";
	//
	// make header row
	$thdr = "<thead><tr>";
	$thdr .= "<th></th>";
	for($i = 1; $i <= count($otr[1]); $i++) {
		$thdr .= "<th>Rack $i</th>";
	}
	$thdr .= "</tr></thead>\n";
	$tbs .= $thdr;
	//
	// make row for each shelf
	for($shelf = 1; $shelf <= count($otr); $shelf++) {
		$tbs .= "<tr>";
		$tbs .= "<th>Shelf $shelf</th>";
		for($rack = 1; $rack <= count($otr[$shelf]); $rack++) {
			$tbs .= "<td>";
			$tbs .= $otr[$shelf][$rack];
			$tbs .= "</td>";
		}
		$tbs .= "</tr>";
	}
	$tbs .= "</table>";
	return $tbs;
}

?>
