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


?>
